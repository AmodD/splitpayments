<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Submerchant;
use App\Models\Tenant;
use Livewire\Attributes\Url;

class CreateSubmerchant extends Component
{
     #[Url(as: 't')]
    public $tenantulid = '';

     #[Url(as: 'm')]
    public $externaltenantreference = '';

    public $tenant_id = 1;
    public $paymentgateway_id = 1;
    public $status = 'inactive';

    public $dba_name = '';
    public $gstn = '';
    public $bank_name = '';
    public $ifsc = '';
    public $account_type = '';
    public $account_number = '';

    #[Computed]
    public function tenant()
    {
        return Tenant::where('ulid', $this->tenantulid)->first();
    }

    public function save()
    {
        Submerchant::create(
            $this->only(['dba_name','gstn','bank_name','ifsc','account_type','account_number','tenant_id','paymentgateway_id','status','externaltenantreference'])
        );
 
        return $this->redirect('/sdk/thankyou');
    }

    public function render()
    {
        if(!$this->tenantulid) return '<div>Please contact the admin. Missing Tenant ID for SPLITPAYMENTS !</div>';
        if(!$this->externaltenantreference) return '<div>Please contact the admin. Missing Unique Sub-Merchant Reference ID for SPLITPAYMENTS !</div>';

        if(!$this->tenant) return '<div>Please contact the admin. Incorrect Tenant ID for SPLITPAYMENTS !</div>';

        if($this->tenant->status == 'inactive') return '<div>Please contact SPLITPAYMENTS admin. '.$this->tenant->name.' is INACTIVE !</div>';

        return view('livewire.create-submerchant');
    }
}
