<?php

namespace App\Operations\Core;

use App\Enums\Core\LogSeverity;
use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use App\Models\AppLog;

/**
 * Service class that handles log entries
 * @property Model       $model         // main object
 * @property string      $message       // human readable message
 * @property LogSeverity $logSeverity   // log severity level
 */
class LogOperation
{
    protected Model       $model;
    protected string      $message;
    protected LogSeverity $logSeverity;
    protected ?string     $detail;

    /**
     * Get the user performing the action.
     * 0 = Automated system function
     * @return int
     */
    public function getUserId(): int
    {
        return user()->id ?? 0;
    }

    /**
     * Return the model id
     * @return int
     */
    private function getModelId(): int
    {
        return $this->model->getKey();
    }

    /**
     * Attempt to get the account_id from the model given.
     * If it is an account just use the ID.
     * @return int|null
     */
    private function getAccountId(): int|null
    {
        if ($this->model instanceof Account)
        {
            return $this->model->id;
        }
        return $this->model->account_id ?? null;
    }

    /**
     * Get the string class name for the type of object
     * being sent to the logger
     * @return string
     */
    private function getType(): string
    {
        return $this->model::class;
    }

    /**
     * Get the LogLevel from the enum types.
     * @return int
     */
    private function getLogLevel(): int
    {
        return $this->logSeverity->value;
    }

    /**
     * The message is the human readable event that
     * is displayed to the user.
     * @return string
     */
    private function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Optionally save additional details for developers
     * to review. For API errors, system errors etc.
     * @return string|null
     */
    private function getDetail(): ?string
    {
        return $this->detail;
    }

    /**
     * Inserts a log entry into database
     * @return void
     */
    private function insert(): void
    {
        AppLog::create([
            'user_id'    => $this->getUserId(),
            'account_id' => $this->getAccountId(),
            'type'       => $this->getType(),
            'type_id'    => $this->getModelId(),
            'log_level'  => $this->getLogLevel(),
            'log'        => $this->getMessage(),
            'detail'     => $this->getDetail(),
        ]);
    }

    /**
     * This method will be mainly used for log entries
     * @param Model       $model
     * @param string      $message
     * @param LogSeverity $logSeverity
     * @param string|null $detail
     * @param Model|null  $old
     * @return void
     */
    public function write(
        Model $model,
        string $message,
        LogSeverity $logSeverity,
        ?string $detail = null,
        ?Model $old = null
    ): void {
        $this->model = $model;
        $this->message = $message;
        $this->logSeverity = $logSeverity;
        if ($old)
        {
            // We were sent in an old object so we need to add additional detail automatically
            $this->detail = $this->compare($model, $old);
        }
        else
        {
            $this->detail = $detail;
        }
        $this->insert();
    }

    /**
     * Compare the current and old model objects and create a human-readable list of
     * changes. This will only work if $tracked is public on each Model.
     * @param Model $model
     * @param Model $old
     * @return string|null
     */
    private function compare(Model $model, Model $old): ?string
    {
        if (get_class($model) != get_class($old)) return null; // Different types. Only compare same.
        $changes = [];
        if (!isset($model->tracked)) return null; // Need to add tracked to model.
        foreach ($model->tracked as $key => $desc)
        {
            if ($model->{$key} != $old->{$key})
            {
                // Check to see if we have a relationship to map for the value data.
                if (preg_match("/\|/", $desc))
                {
                    $m = explode("|", $desc);
                    if ($m[1] == 'money') // If we need to convert an int into money.
                    {
                        $oldValue = moneyFormat($old->{$key});
                        $newValue = moneyFormat($model->{$key});
                    }
                    elseif ($m[1] == 'bool') // If we need to convert an int into money.
                    {

                        $oldValue = (bool) $old->{$key};
                        $oldValue = $oldValue ? "Yes" : "No";
                        $newValue = (bool) $model->{$key};
                        $newValue = $newValue ? "Yes" : "No";
                    }
                    else // If this is a relationship
                    {
                        // This looks something like Agent Name|agent.name
                        $x = explode(".", $m[1]);
                        $oldValue = $old->{$x[0]}->{$x[1]};
                        $newValue = $model->{$x[0]}->{$x[1]};
                    }
                    if (!$oldValue) $oldValue = "Empty";
                    if (!$newValue) $newValue = "Empty";
                    $desc = $m[0];
                    $changes[] = sprintf("%s changed from <b>%s</b> to <b>%s</b>", $desc, $oldValue, $newValue);
                    continue;
                }
                $changes[] = sprintf("%s changed from <b>%s</b> to <b>%s</b>", $desc, $old->{$key} ?:"Empty", $model->{$key}?:"Empty");
            }
        }
        return implode(", ", $changes);
    }
}
