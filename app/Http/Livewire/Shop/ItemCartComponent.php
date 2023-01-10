<?php

namespace App\Http\Livewire\Shop;

use App\Exceptions\LogicException;
use App\Models\BillItem;
use Illuminate\View\View;
use Livewire\Component;

/**
 * ItemCartComponent
 *
 * This class handles the rendering of a single item view.
 */
class ItemCartComponent extends Component
{
    public BillItem $item;
    public string   $addText          = 'Add To Cart';
    public array    $addons           = [];
    public int      $qtySpinner       = 1;
    public string   $errorMessage     = '';
    public array    $reqModelBindings = [];

    /**
     * Track which variation is currently being selected.
     * @var int
     */
    public int $variationSelected = 0;

    public $listeners = [
        'updatedRequirements'
    ];

    /**
     * Prefill Addons array with possible addons
     * @return void
     */
    public function mount(): void
    {
        if ($this->item->reservation_mode)
        {
            $this->addText = "Reserve for $" . number_format($this->item->reservation_price, 2);
        }
        $this->initAddons();
    }

    /**
     * Change the variation selected
     * @param int $id
     * @return void
     */
    public function changeVariation(int $id): void
    {
        $this->variationSelected = $id;
        $this->item = BillItem::find($id);
        $this->initAddons();
    }


    /**
     * Increase Quantity Spinner
     * @return void
     */
    public function increaseQty()
    {
        $this->qtySpinner++;
    }

    /**
     * Decrease Qty spinner but not below 1.
     * @return void
     */
    public function decreaseQty()
    {
        $new = $this->qtySpinner - 1;
        if ($new < 1) $new = 1;
        $this->qtySpinner = $new;
    }

    /**
     * Render the item details
     * @return View
     */
    public function render(): View
    {
        $this->emit('reinitSlider');
        return view('shop.category.item.component');
    }


    /**
     * Add Item to Cart
     * @return void
     */
    public function addItem(): void
    {
        $this->errorMessage = '';
        $cart = cart();
        try
        {
            $item = $cart->addItem($this->item, $this->qtySpinner);
        } catch (LogicException $e)
        {
            $this->errorMessage = $e->getMessage();
            return;
        }
        if (count($this->addons))
        {
            $cart->processAddons($item->uid, $this->addons);
        }
        $this->addText = "Added to Cart!";
        $this->emit('cartUpdated');
        $this->emit('reinitSlider');
    }

    /**
     * Initialze Addons when selecting a new product or switching variations.
     * @return void
     */
    private function initAddons(): void
    {
        foreach ($this->item->addons as $addon)
        {
            $this->addons["add_$addon->id"] = null;
        }
    }



}
