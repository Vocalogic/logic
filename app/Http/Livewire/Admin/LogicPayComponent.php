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
    public         $listeners          = ['logicToken'];
    public string  $message            = "Awaiting New Payment Details..";
    public string  $messageColor       = 'info';
    public bool    $awaitingExpiration = false;
    public string  $expiration         = '';
    public Account $account;
    public string  $token;

    /**
     * Render Component (also spawns listener for the token)
     * @return View
     */
    public function render(): View
    {
        return view('admin.accounts.billing.logic_component');
    }

    /**
     * Receive Token from Emitter
     * @param string $token
     * @return void
     * @throws GuzzleException
     */
    public function logicToken(string $token): void
    {
        $this->message = "Attempting to Validate..";
        $this->token = $token;
        $this->attemptAuthorization();
    }

    /**
     * Attempt to Authorize $1.00 with this token.
     * @return void
     * @throws GuzzleException
     */
    private function attemptAuthorization(): void
    {
        $lp = new LogicPay();
        try
        {
            $lp->addPaymentMethod($this->account, $this->token);
        } catch (LogicException $e)
        {
            $this->message = $e->getMessage();
            $this->messageColor = 'danger';
            return;
        }
        $this->account->refresh();
        $this->messageColor = 'success';
        $this->message = "<span class='text-success'>Card Authorized! Verify Expiration Date</span>";
        $this->awaitingExpiration = true;
    }


    /**
     * Attempt to save Expiration
     * @return mixed
     */
    public function saveExpiration(): mixed
    {
        if (!$this->expiration)
        {
            $this->message = "<span class='text-danger'>Expiration date verification required for validation.</span>";
            return null;
        }
        if (strlen($this->expiration) != 4)
        {
            $this->message = "<span class='text-danger'>Expiration date must be MMYY format.</span>";
            return null;
        }
        $data = [
          'expiration' => $this->expiration
        ];
        $this->account->update(['merchant_metadata' => $data]);
        if (user()->account->id > 1)
        {
            sysact(ActivityType::Account, user()->account->id, "updated their credit card information for ");
            return redirect()->to("/shop/account/profile")->with('message',
                "Your payment method has been updated with " . setting('brand.name') . " Successfully!");
        }
        else
        {
            return redirect()->to("/admin/accounts/{$this->account->id}")
                ->with('message', "Payment Method Updated Successfully!");
        }
    }
}
