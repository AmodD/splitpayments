<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Submerchant;
use App\Models\Tenant;

class CreateSubmerchant extends Component
{
     #[Url(as: 't')]
    public $tenantulid;

     #[Url(as: 'm')]
    public $tenantsubmerchantid = '';

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
        return Tenant::find(1);
    }

    public function save()
    {
        Submerchant::create(
            $this->only(['dba_name','gstn','bank_name','ifsc','account_type','account_number','tenant_id','paymentgateway_id','status'])
        );
 
        return $this->redirect('/sdk/thankyou');
    }

    public function render()
    {
        return view('livewire.create-submerchant');
    }
}
