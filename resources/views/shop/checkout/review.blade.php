@livewire('shop.guest-cart-component', ['mini' => true])

<button class="btn text-white bg-success" wire:click="verifyCart"><i class="fa fa-floppy-disk"></i>&nbsp;&nbsp; Continue (Total: ${{moneyFormat($total)}})</button>
