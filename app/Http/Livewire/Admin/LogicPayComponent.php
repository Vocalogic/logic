<?php

namespace App\Http\Livewire\Admin;

use App\Enums\Core\ActivityType;
use App\Exceptions\LogicException;
use App\Models\Account;
use App\Operations\Integrations\Merchant\LogicPay;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class LogicPayComponent extends Component
{
    public         $listeners       = ['logicToken'];
    public string  $message         = "Enter New Card Number to Preauthorize";
    public string  $messageColor    = 'info';
    public string  $expiration      = '';
    public string  $expiry          = '';
    public string  $postal          = '';
    public string  $cvv             = '';
    public Account $account;
    public string  $token;
    public bool    $canAttempt      = false;
    public bool    $validExpiration = false;
    public bool    $validPostal     = false;
    public bool    $validCVV        = false;
    public bool    $complete        = false;

    /**
     * Render Component (also spawns listener for the token)
     * @return View
     */
    public function render(): View
    {
        $this->validExpiration = (strlen($this->expiration) == 4 && is_numeric($this->expiration));
        $this->validPostal = strlen($this->postal) == 5 & is_numeric($this->postal);
        $this->validCVV = strlen($this->cvv) > 2 && is_numeric($this->cvv);
        $this->canAttempt = $this->validExpiration && $this->validPostal && $this->validCVV && $this->token;
        return view('admin.accounts.billing.logic_component');
    }

    /**
     * Receive Token from Emitter
     * @param string $token
     * @return void
     */
    public function logicToken(string $token): void
    {
        $this->message = "Encrypted Card Number, Awaiting Input..";
        $this->token = $token;
    }

    /**
     * Attempt to Authorize Card
     * @return void
     * @throws GuzzleException
     */
    public function attemptAuthorization(): void
    {
        $lp = new LogicPay();
        try
        {
            $lp->addPaymentMethod($this->account, $this->token, $this->expiration, $this->cvv, $this->postal);
        } catch (LogicException $e)
        {
            $this->message = $e->getMessage();
            $this->messageColor = 'danger';
            return;
        }
        $this->account->refresh();
        $this->messageColor = 'success';
        $this->message = "Card Successfully Authorized";
        $this->complete = true;
        $data = [
            'expiration' => $this->expiration
        ];
        $this->account->update(['merchant_metadata' => $data]);
    }



}
