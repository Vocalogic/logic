<?php

namespace App\Http\Livewire\Shop;

use App\Enums\Core\CommKey;
use App\Exceptions\LogicException;
use App\Models\BillItem;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ShopAssistComponent extends Component
{
    public ?string $skey = null;
    public int $currentKeepAlive = 0;
    public int $sendKeepAlive = 3; // 2 sec polling = 6 seconds of keepalive.


    /**
     * Mount ShopAssistant
     * @return void
     * @throws LogicException
     */
    public function mount(): void
    {
        $this->assist();
    }

    /**
     * Begin Polling
     * @return View
     */
    public function render(): View
    {
        return view('shop.assist_component');
    }

    /**
     * Main Polling Routine
     * @return void
     * @throws LogicException
     */
    public function assist(): void
    {
        $this->skey = session(CommKey::LocalCartCrossSession->value);
        if ($this->skey == null)
        {
            // By calling this method, we do in fact are an active session and should restack our session
            // onto the bus.
            $this->skey = cart()->setCrossSession();
            // We won't re-read from the session here because sbus is updating on the stack in the previous comnmand.
        }
        $obj = sbus()->get($this->skey);
        if (is_null($obj)) return; // No session built or has expired. Nothing to do here. Both above failed.

        if (isset($obj->command) && !isset($obj->executed))
        {
            // We have a command in the pipe
            $this->processCommand($obj->command, $obj);
        }
        else
        {
            $this->currentKeepAlive++;
            if ($this->currentKeepAlive >= $this->sendKeepAlive)
            {
                // No command, just let sbus know we're still alive.
                sbus()->ping($this->skey);
                $this->currentKeepAlive = 0;  // Reset KA
            }
        }
    }

    /**
     * Process a directive to browser or perform cart action.
     * @param string $command
     * @param object $obj
     * @return void
     * @throws LogicException
     */
    private function processCommand(string $command, object $obj) : void
    {
        $x = explode("|", $command);
        switch ($x[0])
        {
            case 'sendto' :
                $this->emit("assistDirect", $x[1]);
                break;
            case 'reload' :
                $this->emit("assistReload");
                break;
            case 'updateItem' :
                cart()->updateItem($obj->itemRef, $obj->itemData);
                $this->emit("cartUpdated");
                break;
            case 'removeItem' :
                cart()->removeItem($obj->itemRef);
                $this->emit('cartUpdated');
                break;
            case 'addItem' :
                $item = BillItem::find($obj->itemRef);
                $qty = $obj->itemData;
                cart()->addItem($item, $qty);
                $this->emit('cartUpdated');
                break;
            case 'updateCart' :
                $this->emit('cartUpdated');
                break;
            case 'sendMessage' :
                $this->emit('sendMessage', $obj->message);
                break;
        }
        sbus()->setExecuted($this->skey);
    }

}
