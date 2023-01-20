<?php

use App\Enums\Core\ActivityType;
use App\Enums\Core\CommKey;
use App\Enums\Core\EventType;
use App\Enums\Core\IntegrationRegistry;
use App\Enums\Core\IntegrationType;
use App\Enums\Core\MetricType;
use App\Enums\Core\ModuleRegistry;
use App\Models\Account;
use App\Models\Activity;
use App\Models\Integration;
use App\Models\LOFile;
use App\Models\LOLog;
use App\Models\Metric;
use App\Models\Provider;
use App\Models\Setting;
use App\Models\User;
use App\Operations\Admin\MorningStatus;
use App\Operations\API\Control;
use App\Operations\Shop\ShopBus;
use App\Operations\Shop\ShopOperation;
use App\Structs\STemplate;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\CommonMarkConverter;

if (!function_exists('setting'))
{

    /**
     * User helper
     * @returns User|null
     */
    function user(): ?User
    {
        return auth()->user();
    }

    /**
     * Get or set a new setting.
     * @param string      $ident
     * @param string|null $value
     * @param bool        $asSelectable Used for dropdown opts or tags explode
     * @param bool        $fromValues   Use Values instead of opts (for tags)
     * @return string|array|null
     */
    function setting(
        string $ident,
        ?string $value = null,
        bool $asSelectable = false,
        bool $fromValues = false
    ): string|array|null {
        $setting = Setting::where('ident', $ident)->first();
        if (!$setting && !$value) return null;
        if (!$setting && $value)
        {
            $setting = (new Setting)->create([
                'ident'    => $ident,
                'value'    => $value,
                'question' => "Dynamic",
                'type'     => 'text',
                'default'  => $value,
                'help'     => "Dynamic Setting",
                'category' => 'Dynamic'
            ]);
        }
        if ($asSelectable)
        {
            $x = explode(",", $fromValues ? $setting->value : $setting->opts);
            $data = [];
            foreach ($x as $v)
            {
                $data[$v] = $v;
            }
            return $data;
        }
        if (!$value)
        {
            return $setting->value ?: $setting->default;
        }
        else
        {
            $setting->update(['value' => $value]);
            return $value;
        }
    }

    /**
     * Convert markdown to html
     * @param string|null $convert
     * @return string
     */
    function _markdown(?string $convert = null): string
    {
        if (!$convert) return '';
        $converter = new CommonMarkConverter([
            'html_input'         => 'strip',
            'allow_unsafe_links' => false,
            'renderer'           => [
                'block_separator' => "\n",
                'inner_separator' => "\n",
                'soft_break'      => "\n",
            ],
        ]);
        return $converter->convert($convert);
    }

    /**
     * Get a file from an id.
     * Keys are name, description, internal, url, hash
     * @param int|null $id
     * @param bool     $asStream
     * @param bool     $forcedDownload
     * @return mixed
     */
    function _file(?int $id, bool $asStream = false, bool $forcedDownload = true): mixed
    {
        if (!$id) return null;
        $file = LOFile::find($id);
        if (!$file) return null;
        // Do we need to have auth?
        if (auth()->guest() && $file->auth_required) abort(401);
        // Ok we should stream the file.
        //  $file->update(['views' => $file->views + 1]); // Count times retreieved.

        if ($asStream)
        {
            if ($forcedDownload)
            {
                try
                {
                    return Storage::download(
                        sprintf("%s/%s", $file->location, $file->real),
                        $file->filename
                    );
                } catch (Exception $e)
                {
                    $file->delete();
                    info("Error Attempting to Render File: $file->id ($file->location / $file->real / $file->filename) - " . $e->getMessage());
                }
            }
            else
            {
                return Storage::download(
                    sprintf("%s/%s", $file->location, $file->real),
                    $file->filename,
                    [
                        'Content-Type'        => $file->mime_type,
                        'Content-Disposition' => sprintf("inline; filename='%s'", $file->filename)
                    ]
                );
            }
        }
        $ext = "unk"; // Default unknown extension.
        $fext = explode(".", $file->filename);
        if (isset($fext[1])) $ext = $fext[1];
        return (object)[
            'name'        => $file->filename,
            'description' => $file->description,
            'internal'    => sprintf("%s/app/%s/%s", storage_path(), $file->location, $file->real),
            'url'         => sprintf("%s/file/%s.%s", setting('brand.url'), $file->hash, $ext),
            'relative'    => sprintf("/file/%s.%s", $file->hash, $ext),
            'extension'   => $ext,
            'mime_type'   => $file->mime_type,
            'size'        => $file->filesize,
            'hash'        => $file->hash
        ];
    }

    /**
     * Log an event or notification
     * @param EventType   $category
     * @param EventType   $type
     * @param string      $message
     * @param string|null $title
     * @param string|null $url
     * @param int|null    $user_id
     * @param int         $account_id
     * @return void
     */
    function _log(
        EventType $category,
        EventType $type,
        string $message,
        string $title = null,
        string $url = null,
        ?int $user_id = null,
        int $account_id = 1
    ): void {
        if ((!$user_id) && !auth()->guest())
        {
            $user_id = user()->id;
        }
        if ($account_id > 1)
        {
            (new LOLog)->create([
                'category'   => $category,
                'message'    => $message,
                'title'      => $title,
                'link'       => $url,
                'account_id' => $account_id,
                'type'       => $type,
                'user_id'    => $user_id
            ]);
        }
        else
        {
            foreach (User::where('account_id', 1)->get() as $user)
            {
                (new LOLog)->create([
                    'category'   => $category,
                    'message'    => $message,
                    'title'      => $title,
                    'link'       => $url,
                    'account_id' => $account_id,
                    'type'       => $type,
                    'user_id'    => $user->id
                ]);
            }
        }
    }


    /**
     * Autofire a templated email.
     * @param string      $ident
     * @param User|null   $user
     * @param array|null  $models
     * @param array|null  $attachments
     * @param string|null $ccEmail
     * @param string|null $ccName
     * @param bool|null   $sendEmail
     * @return STemplate|null
     */
    function template(
        string $ident,
        ?User $user,
        ?array $models = [],
        ?array $attachments = [],
        ?string $ccEmail = null,
        ?string $ccName = null,
        bool $sendEmail = true,
    ): null|STemplate {
        try
        {
            $e = new STemplate(
                ident: $ident,
                user: $user,
                models: $models, attachments: $attachments,
                ccEmail: $ccEmail,
                ccName: $ccName
            );
            if (!$sendEmail) return $e;
            $e->fire();
        } catch (Exception $e)
        {
            info("Failure to send email: " . $e->getMessage());
            _log(EventType::Mail, EventType::SEV_ERROR, $e->getMessage(), null, null, user()?->id,
                user()?->account?->id);
        }
        return null;
    }

    /**
     * Insert a new metric into the database. Leave date blank to use today.
     * @param MetricType   $type
     * @param float        $value
     * @param Account|null $account
     * @param Carbon|null  $date
     * @param string|null  $detail Detail Field
     * @return void
     */
    function _imetric(
        MetricType $type,
        float $value,
        ?Account $account = null,
        Carbon $date = null,
        ?string $detail = null
    ) {
        if (!$date) $date = now();
        (new Metric)->create([
            'account_id' => $account ? $account->id : 0,
            'metric'     => $type->value,
            'value'      => $value,
            'stamp'      => $date,
            'detail'     => $detail
        ]);
    }

    /**
     * Get Metrics for a particular day for an account. Optionally
     * request a type if you only need that type.
     * @param Carbon          $date
     * @param Account|null    $account
     * @param MetricType|null $type
     * @param string|null     $detail
     * @return Collection
     */
    function _metric(
        Carbon $date,
        ?Account $account = null,
        ?MetricType $type = null,
        ?string $detail = null
    ): Collection {
        $collect = Metric::where('stamp', $date);
        if ($account)
        {
            $collect = $collect->where('account_id', $account->id);
        }
        if ($type)
        {
            $collect = $collect->where('metric', $type->value);
        }
        if ($detail)
        {
            $collect = $collect->where('detail', $detail);
        }
        return $collect->get();
    }

    /**
     * Get Metrics for a range of days. Optionally
     * request a type if you only need that type.
     * @param Carbon          $start
     * @param Carbon          $end
     * @param Account|null    $account
     * @param MetricType|null $type
     * @param bool            $diff
     * @param string|null     $detail
     * @return Collection
     */
    function _metrics(
        Carbon $start,
        Carbon $end,
        ?Account $account = null,
        ?MetricType $type = null,
        bool $diff = false,
        ?string $detail = null
    ): Collection {
        $collect = Metric::whereDate('stamp', '>=', $start)->whereDate('stamp', '<=', $end);
        if ($account)
        {
            $collect = $collect->where('account_id', $account->id);
        }
        if ($type)
        {
            $collect = $collect->where('metric', $type->value);
        }
        if ($detail)
        {
            $collect = $collect->where('detail', $detail);
        }
        $collect = $collect->orderBy('stamp', 'DESC');
        $collection = $collect->get();
        if (!$diff) return $collection;

        // Get differential.
        $starting = Metric::whereDate('stamp', '>=', $start->copy()->subDay()->startOfDay())
            ->whereDate('stamp', "<=", $start->copy()->subDay()->endOfDay())
            ->where('metric', $type->value)->first();
        $val = 0;
        if ($starting && $starting->value) $val = $starting->value;
        $new = collect();
        foreach ($collection as $entry)
        {
            // We want to get a diff from previous.
            if ($entry->value > $val)
            {
                $entry->value = $entry->value - $val; // New val is 10, old is 5.. we save 5.
                $val = $entry->value;
            }
            else $entry->value = 0;
            $new->add($entry);
        }
        return $new;
    }


    /**
     * Basic Byte Formatter
     * @param     $size
     * @param int $precision
     * @return string
     */
    function formatBytes($size, int $precision = 2): string
    {
        if (!is_numeric($size)) return "?";
        $base = log($size, 1024);
        $suffixes = array('', 'K', 'M', 'G', 'T');
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    /**
     * Helper to only return digits, extract commas, etc.
     * @param string|null $text
     * @return float
     */
    function onlyNumbers(?string $text): float
    {
        if (!$text) return 0.0;
        return (float)preg_replace("/[^0-9.-]/", '', $text);
    }

    /**
     * Add a system activity
     * @param ActivityType $type
     * @param int          $refid
     * @param string       $action
     * @param string|null  $postData
     * @param bool         $forceAsSystem
     * @return void
     */
    function sysact(ActivityType $type, int $refid, string $action, ?string $postData = '', bool $forceAsSystem = false): void
    {
        $user = auth()->guest() ? 0 : user()->id;
        if ($forceAsSystem) $user = 0;
        (new Activity)->create([
            'type'     => $type->value,
            'refid'    => $refid,
            'system'   => true,
            'post'     => $postData,
            'activity' => $action,
            'user_id'  => $user
        ]);
    }


    /**
     * Does this installation have an integration from a category.
     * @param IntegrationType $type
     * @return bool
     */
    function hasIntegration(IntegrationType $type): bool
    {
        foreach (IntegrationRegistry::cases() as $case)
        {
            $i = Integration::where('ident', $case->value)->first();
            if ($i && $i->enabled && $case->getCategory() == $type) return true;
        }
        return false;
    }

    /**
     * Human readable telephone number
     * @param string $number
     * @return string
     */
    function makeTn(string $number): string
    {
        return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '$1.$2.$3', $number);
    }

    /**
     * Get the first (or only) integration. Should call hasIntegration first.
     * @param IntegrationType $type
     * @return IntegrationRegistry|null
     */
    function getIntegration(IntegrationType $type): ?IntegrationRegistry
    {
        foreach (IntegrationRegistry::cases() as $case)
        {
            $i = Integration::where('ident', $case->value)->first();
            if ($i && $i->enabled && $case->getCategory() == $type) return $case;
        }
        return null;
    }

    /**
     * Get Current Mode (dark or light)
     * @return string
     */
    function currentMode(): string
    {
        if (auth()->guest()) return setting('brand.contrast');
        return user()->preference('mode') ?: setting('brand.contrast');
    }

    /**
     * Get button mode
     * @return string
     */
    function bm(): string
    {
        return currentMode() == 'dark' ? 'light-' : '';
    }

    /**
     * Get Button Mode Alert for Mode
     * @return string
     */
    function bma(): string
    {
        return currentMode() == 'dark' ? 'bg-light-' : 'alert-';
    }

    /**
     * Is this an admin account or not (used for customer checking)
     * @return bool
     */
    function isAdmin(): bool
    {
        if (auth()->guest()) return false;
        if (user()->account_id > 1) return false;
        if (user()->acl->value === \App\Enums\Core\ACL::ADMIN->value) return true;
        return false;
    }

    /**
     * Is user logged in a sales agent?
     * @return bool
     */
    function isSales(): bool
    {
        if (auth()->guest()) return false;
        return user()->acl->value === \App\Enums\Core\ACL::SALES->value;
    }

    /**
     * Get Current Version running. This will be used
     * to compare against the latest version to see if
     * customers should be asked to upgrade.
     *
     * @return object
     */
    function currentVersion(): object
    {
        return json_decode(file_get_contents(app_path() . "/logic_version.json"));
    }

    /**
     * If our version is higher than the master, then we should show
     * the user that this is the development instance and not show the
     * "upgrade is available" on the dashboard
     * @return bool
     */
    function isInDevelopment(): bool
    {
        $current = (int)str_replace(".", "", currentVersion()->version);
        $stable = (int)str_replace(".", "", latestVersion()->version);
        return $current > $stable;
    }

    /**
     * Get the latest version from master.
     * @return object
     */
    function latestVersion(): object
    {
        if (cache(CommKey::GlobalLatestVersionCache->value))
        {
            return cache(CommKey::GlobalLatestVersionCache->value);
        }
        try
        {
            $file = file_get_contents("https://raw.githubusercontent.com/Vocalogic/logic/master/app/logic_version.json");
            $obj = json_decode($file);
            cache([CommKey::GlobalLatestVersionCache->value => $obj], CommKey::GlobalLatestVersionCache->getLifeTime());
            return $obj;
        } catch (Exception)
        {
            // Don't crash if file doesn't exist for some reason.
            return (object)[
                'version'   => "N/A",
                'summary'   => "N/A",
                'changelog' => "#"
            ];
        }
    }


    /**
     * Get partner code, and other license information. This also
     * enables SMS for 2FA and other licensed services.
     * @return ?object
     * @throws \App\Exceptions\LogicException
     */
    function license(): ?object
    {
        if (!setting('brand.license')) return null;
        $c = new Control();
        try
        {
            $lic = $c->getLicense();
        } catch (GuzzleException)
        {
            return null;
        }
        if (!$lic || !$lic->success) return null;
        $obj = (object)[];

        $obj->partner_code = $lic->partner_code;

        return $obj;
    }

    /**
     * Hook into a default path for adding onto different core components
     * @param string $location
     * @param array  $data
     * @return string|null
     */
    function moduleHook(string $location, array $data = []): ?string
    {
        $vData = null;
        if (sizeOf(ModuleRegistry::cases()) == 0) return null; // No modules found. Base Install
        foreach (ModuleRegistry::cases() as $mod)
        {
            if ($mod->isEnabled())
            {
                $v = strtolower($mod->value) . "::" . $location;
                if (view()->exists($v))
                {
                    $vData .= view($v)->with($data)->render();
                }
            }
        }
        return $vData;
    }

    /**
     * Convert the integer value of a money field and return a decimal version.
     * @param int|null $value
     * @param bool     $formatted
     * @return string
     */
    function moneyFormat(?int $value = 0, bool $formatted = true): string
    {
        if ($value == 0 || !$value) return '0.00';
        $value = $value / 100;
        return $formatted ? number_format($value, 2) : $value;
    }

    /**
     * Take a float value (generally from an input) and return
     * the integer form
     * @param string|null $value
     * @return int
     */
    function convertMoney(string $value = null): int
    {
        $value = onlyNumbers($value);
        if (!$value) return 0;
        $value = $value * 100;
        return (int)$value;
    }

    /**
     * Get the cart object.
     * @return ShopOperation
     */
    function cart(): ShopOperation
    {
        return new ShopOperation();
    }

    /**
     * Get the shop bus.
     * @return ShopBus
     */
    function sbus(): ShopBus
    {
        return new ShopBus();
    }

    /**
     * Get morning status object
     * @return array
     */
    function morning(): array
    {
        return MorningStatus::status();
    }

}
