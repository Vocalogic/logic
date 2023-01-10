<div lcass="text-content readable">
{!! $steps[$step]['tos_data'] !!}
</div>
@if(!isset($tosAccept[$step]) || !$tosAccept[$step] == true)
    <button class="btn text-white bg-success" wire:click="acceptTos()"><i class="fa fa-check"></i> Accept Terms of Service</button>
@endif
