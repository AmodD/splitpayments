<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}

  <form wire:submit="save">
    <input type="hidden" wire:model="tenant_id" value="1">
    <input type="hidden" wire:model="paymentgateway_id">
    <input type="hidden" wire:model="status">
    Tenant
    {{ $this->tenant->name }}
    <br><br>
    DBA Name
    <input type="text" wire:model="dba_name">
    <br><br>
    GSTN
    <input type="text" wire:model="gstn">
    <br><br>
    Bank Name
    <input type="text" wire:model="bank_name">
    <br><br>
    IFSC
    <input type="text" wire:model="ifsc">
    <br><br>
    Account Type
    <input type="text" wire:model="account_type">
    <br><br>
    Account Number
    <input type="text" wire:model="account_number">
    <br><br>
    <button type="submit">Save</button>
    <br><br><hr>
  </form>

  {!! \Illuminate\Foundation\Inspiring::quote() !!}
</div>
