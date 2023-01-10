<?php

namespace App\Http\Livewire;

use App\Enums\Core\CommKey;
use App\Jobs\DispatchEmail;
use App\Operations\API\Control;
use App\Structs\SEmail;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

class VerificationComponent extends Component
{
    /**
     * If this is a tfa verification, we will handle our
     * verification slightly differently
     * @var bool
     */
    public bool $tfa = false;
    /**
     * Verifying from sms/email, etc.
     * @var string
     */
    public string $verifyMethod;
    /**
     * Are we in progress of verification?
     * @var bool
     */
    public bool $inProgress = false;
    /**
     * How long do we wait before allowing changing of email or phone number.
     * @var int
     */
    public int $disableWait = 30;

    /**
     * Once we click verify we will increment this value to compare
     * for the disableWait.
     * @var Carbon
     */
    public Carbon $startTime;

    public int $maxAttempts = 5;
    public int $tries       = 0;

    public string $verificationInput = '';

    public string $errorMessage = '';

    public bool $verified  = false;
    public bool $lockInput = false;

    /**
     * Human-readable status
     * @var string
     */
    public string $verificationStatus = "Not Started";

    public string $email;
    public string $phone;

    /**
     * Verification Code Generated
     * @var string
     */
    public string $code;

    /**
     * Mount the required variables.
     * @return void
     */
    public function mount(): void
    {
        $this->email = '';
        $this->phone = '';
        $this->verifyMethod = !$this->tfa ? setting('shop.verification') : setting('account.2fa_method');
        if ($this->tfa)
        {
            $this->email = Str::mask(user()->email, '*', -9);
            if (user()->phone)
            {
                $this->phone = Str::mask(user()->phone, '*', -5);
            }
            $this->lockInput = true;
            user()->generateTFA();
        }
    }


    /**
     * Render Verification Component
     * @return View
     */
    public function render(): View
    {
        if (!$this->verified)
        {
            return view('shop.verify');
        }
        else return view('shop.verify_success');
    }

    /**
     * Send email or send SMS with code.
     * @return void
     */
    public function startVerification(): void
    {
        if (!$this->tfa)
        {
            if ($this->verifyMethod == 'Email' && !filter_var($this->email, FILTER_VALIDATE_EMAIL))
            {
                $this->errorMessage = 'A valid email address is required.';
                return;
            }
        }

        $this->inProgress = true;
        $this->verificationStatus = 'Waiting for Response';
        $this->code = (string)mt_rand(111111, 999999);
        $email = $this->tfa ? user()->email : $this->email; // Fix for mask
        // If email send the email.
        if ($this->verifyMethod == 'Email')
        {
            $data = "Your " . setting('brand.name') . " verification code is: <b>$this->code</b>.";
            $email = new SEmail(
                subject: setting('brand.name') . " Verification Code - $this->code",
                toEmail: $email,
                toName: "Guest Quote",
                view: 'emails.templated',
                attachments: [],
                viewData: ['user' => (object)['first' => "Guest"], 'content' => $data]
            );
            $this->startTime = now();
            dispatch(new DispatchEmail($email));
        }
        elseif ($this->verifyMethod == 'SMS')
        {
            $data = "Your " . setting('brand.name') . " verification code is: <b>$this->code</b>.";
            $c = new Control();

            try
            {
                $phone = $this->tfa ? user()->phone : $this->phone;
                $c->sendSMS($phone, $data);
                $this->startTime = now();
            } catch (Exception|GuzzleException)
            {
                $this->errorMessage = "Unable to send SMS Message. The number was either invalid or another problem occurred.";
                return;
            }
        }

        // if SMS send the text.
    }

    /**
     * Validate Input
     * @return mixed
     */
    public function verifySubmit(): mixed
    {
        $this->tries++;
        $this->errorMessage = '';
        if ($this->verificationInput != $this->code)
        {
            $amount = $this->maxAttempts - $this->tries;
            $this->errorMessage = "Invalid Code Entered. You have $amount tries remaining.";
            return null;
        }
        // Code Accepted
        if (!$this->tfa)
        {
            $this->inProgress = false;
            $this->verified = true;
            $this->emit('verificationComplete');
            session([CommKey::LocalVerificationSession->value => true]);
        }
        else
        {
            // Update TFA and redirect
            user()->authorizeIp();
            return redirect()->to("/");
        }
        return null;
    }
}
