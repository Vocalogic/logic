<?php

namespace App\Http\Livewire\Shop;

use App\Enums\Core\CommKey;
use App\Models\Account;
use App\Models\BillItem;
use App\Models\Quote;
use App\Models\Term;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;

class CheckoutComponent extends Component
{
    public ?Quote   $quote;
    public ?Account $account;
    public array    $items    = [];
    public array    $steps    = [];
    public int      $step     = 0;
    public array    $data     = [];
    public string   $stepView = '';
    public float    $total    = 0;
    public bool     $verified = false;

    public string $contractTerm = '0';
    public string $errorMessage = '';

    public string $signature = '';
    public string $signName  = '';

    protected $listeners = [
        'setSignature',
        'cartUpdated',
        'verificationComplete'
    ];

    // Step Data Fill
    public array $info      = [];
    public array $tosAccept = [];

    /**
     * Enable Execute after verification
     * @return void
     */
    public function verificationComplete(): void
    {
        $this->verified = true;
    }


    /**
     * Setup Checkout process
     * @return void
     */
    public function mount(): void
    {
        if (session(CommKey::LocalQuoteSession->value))
        {
            $this->quote = session(CommKey::LocalQuoteSession->value);
            if (isset($this->quote->id) && $this->quote->lead)
            {
                $this->signName = $this->quote->lead->contact;
                $this->verified = true;
            }
        }
        else $this->quote = new Quote;

        $this->populateCart();
        $this->createSteps();
        $this->fillFromQuote();
        $this->setStepView();
        if (!auth()->guest())
        {
            $this->signName = auth()->user()->name;
        }

        // Set Verification status if found in the session.
        if (session(CommKey::LocalVerificationSession->value))
        {
            $this->verified = true;
        }
        // Set Contract Term if Found
        if (session(CommKey::LocalContractSession->value))
        {
            $this->contractTerm = session(CommKey::LocalContractSession->value);
        }
    }

    /**
     * When a cart is updated, our checkout button needs to change.
     * @return void
     */
    public function cartUpdated(): void
    {
        $this->populateCart();
    }

    /**
     * Gets signature from livewire emit event.
     * @param string $value
     * @return void
     */
    public function setSignature(string $value): void
    {
        $this->signature = $value;
    }


    /**
     * Render Checkout Process
     * @return View
     */
    public function render(): View
    {
        if ($this->total > 0)
        {
            return view('shop.checkout.component');
        }
        else return view('shop.cart.empty');
    }

    /**
     * Create steps based on contract, quote, TOS, etc.
     * @return void
     */
    private function createSteps(): void
    {
        $this->steps[] = [
            'icon'     => 'qvzrpodt',
            'complete' => false,
            'title'    => "Verify Cart",
            'view'     => 'review'
        ];

        // Allow Self-Contracting Selection
        if (auth()->guest() && setting('quotes.selfterm') == 'Yes')
        {
            $cart = cart();
            if ($cart->totalMonthly > 0) // only contract people with monthly services
            {
                $this->steps[] = [
                    'icon'     => 'rgyftmhc',
                    'complete' => false,
                    'title'    => "Discount Contract Term",
                    'view'     => 'contract'
                ];
            }
        }


        if (!auth()->guest())
        {
            $this->steps[] = [
                'icon'     => 'nxaaasqe',
                'complete' => false,
                'title'    => "Execute Order",
                'view'     => 'execute'
            ];
            return;
        }
        // Step 2: Unless there is an account we need to either validate the lead info
        // or we need to just make the fields blank and get info.
        $this->steps[] = [
            'icon'     => 'imamsnbq',
            'complete' => false,
            'title'    => "Your Information",
            'view'     => 'info'
        ];
        $toses = $this->getTOS();
        if (!empty($toses))
        {
            foreach ($toses as $tos)
            {
                $this->steps[] = [
                    'icon'     => 'puvaffet',
                    'complete' => false,
                    'title'    => "$tos->name",
                    'view'     => 'tos',
                    'tos_data' => $tos->convert([])
                ];
            }
        }

        // Final Step - Execute
        $this->steps[] = [
            'icon'     => 'nxaaasqe',
            'complete' => false,
            'title'    => "Execute Order",
            'view'     => 'execute'
        ];

    }

    /**
     * Manual Step View
     * @param int $idx
     * @return void
     */
    public function setStep(int $idx)
    {
        $this->step = $idx;
        $this->setStepView();


    }

    /**
     * Set our step view for rendering
     * @return void
     */
    private function setStepView(): void
    {
        $this->stepView = $this->steps[$this->step]['view'];
        if (isset($this->steps[$this->step]) && $this->steps[$this->step]['view'] == 'execute')
        {
            $this->emit('initSignature'); // Enable sig pad if we are on that view.
        }
    }

    /**
     * Get all terms that need to be agreed to in order to proceed with this quote.
     * @return array
     */
    private function getTOS(): array
    {
        $all = [];
        if ($this->quote && $this->quote->id)
        {
            $all = $this->quote->getTOSArray();
        }
        else
        {
            // Using Cart
            foreach ($this->items as $item)
            {
                $i = BillItem::find($item['id']);
                if ($i->terms)
                {
                    if (!in_array($i->terms->id, $all))
                    {
                        $all[] = $i->terms->id;
                    }
                }
            }
        }
        // Build collection for receiving function.
        if (empty($all)) return [];
        $data = [];
        foreach ($all as $id)
        {
            $data[] = Term::find($id);
        }
        return $data;
    }

    /**
     * Save our info to our lead data if we have a lead. Otherwise, just keep it for execution.
     * @return void
     */
    public function saveInfo(): void
    {
        if ($this->quote && $this->quote->lead)
        {
            $this->quote->lead->update($this->info);
        }
        if ($this->validateInfo())
        {
            $this->errorMessage = $this->validateInfo();
            return;
        }
        $this->moveForward();
    }

    /**
     * Fill some data that we may have in the lead already.
     * @return void
     */
    private function fillFromQuote(): void
    {
        if (!$this->quote || !$this->quote->id) return;
        if ($this->quote->account)
        {
            $this->verified = true;
        }
        if (!$this->quote->lead) return;
        $this->info = [
            'company'  => $this->quote->lead->company,
            'contact'  => $this->quote->lead->contact,
            'address'  => $this->quote->lead->address,
            'address2' => $this->quote->lead->address2,
            'city'     => $this->quote->lead->city,
            'state'    => $this->quote->lead->state,
            'zip'      => $this->quote->lead->zip,
            'email'    => $this->quote->lead->email,
            'phone'    => $this->quote->lead->phone
        ];
    }

    /**
     * Just move forward in the step.
     * @return void
     */
    public function moveForward(): void
    {
        $this->step = $this->step + 1;
        $this->setStepView();

    }

    /**
     * Accept TOS and move on.
     * @return void
     */
    public function acceptTos(): void
    {
        $this->tosAccept[$this->step] = true;
        $this->moveForward();
    }

    /**
     * Get signature from session
     * @return string|null
     */
    public function getSignature() : ?string
    {
        return session(CommKey::LocalSignatureData->value);
    }

    /**
     * Execute Cart
     * @return RedirectResponse|null
     */
    public function execute(): mixed
    {
        $this->errorMessage = '';
        if (!$this->getSignature())
        {
            $this->errorMessage = "You must sign in the box below, agreeing to the terms and conditions of your order before proceeding";
            return null;
        }
        if (!$this->signName)
        {
            $this->errorMessage = "You must enter your name in the Signer's name field.";
            return null;
        }

        if ($this->validateInfo())
        {
            $this->errorMessage = $this->validateInfo();
            return null;
        }

        $cart = cart();
        $cart->infoData = $this->info;
        $cart->execute($this->signName, $this->getSignature());
        return redirect()->to("/shop/account");
    }

    /**
     * Validate Cart Checkout
     * @return string|null
     */
    private function validateInfo(): ?string
    {
        if ($this->quote && $this->quote->account) return null; // Account is fine.
        if (!isset($this->info['contact']) || !$this->info['contact'])
        {
            return "Your contact name is missing. Please update the name for this order.";
        }

        if (!isset($this->info['company']) || !$this->info['company'])
        {
            $this->info['company'] = $this->info['contact'];
        }

        if (!isset($this->info['email']) || !$this->info['email'])
        {
            return "A valid email address is required.";
        }

        if (!filter_var($this->info['email'], FILTER_VALIDATE_EMAIL))
        {
            return "A valid email address is required for an order.";
        }

        if (User::where('email', $this->info['email'])->count())
        {
            return "This email address already exists. Please use another email or sign into your account.";
        }
        if (!isset($this->info['phone']) || !$this->info['phone'])
        {
            return "A valid contact phone number is required for your order.";
        }

        if (!isset($this->info['address']) || !$this->info['address'])
        {
            return "A valid street address is required.";
        }

        if (!isset($this->info['city']) || !$this->info['city'])
        {
            return "A valid city for your street address is required.";
        }

        if (!isset($this->info['state']) || !$this->info['state'])
        {
            return "A valid state for your street address is required.";
        }

        if (!isset($this->info['zip']) || !$this->info['zip'])
        {
            return "A valid zip code for your street address is required.";
        }

        if (!isset($this->info['password']) || !$this->info['password'])
        {
            return "You must set a password for your account.";
        }

        if (!isset($this->info['password2']) || !$this->info['password2'])
        {
            return "You must re-enter your password for your account.";
        }

        if ($this->info['password'] != $this->info['password2'])
        {
            return "Passwords do not match. Please re-enter your password.";
        }

        if (strlen($this->info['password']) < 6)
        {
            return "Password must be at least 6 characters long.";
        }


        return null;
    }

    /**
     * Populate our items array from a guest cart.
     * @return void
     */
    private function populateCart(): void
    {
        $cart = cart();
        $this->items = $cart->items;
        $this->total = $cart->total;
    }


    /**
     * Verify and move forward
     * @return void
     */
    public function verifyCart(): void
    {
        $this->moveForward();
    }

    /**
     * Adjust pricing based on contract terms provided
     * @return void
     */
    public function updateContractTerm(): void
    {
        $cart = cart();
        $cart->setGuestContractTerm($this->contractTerm);
        $this->populateCart();
    }


}
