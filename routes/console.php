<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use App\Models\Tenant;
use App\Models\Paymentgateway;
use App\Models\Submerchant;

use Symfony\Component\Uid\Ulid;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('splitpay:create-tenant {name}', function (string $name) {
    $tenant = new Tenant;
      
    $tenant->ulid = Str::ulid()->toRfc4122();
    $tenant->name = $name;
    $tenant->secret = (string) Str::ulid();
    $tenant->status = 'inactive';

    $tenant->save();

    $this->info("Created a new Tenant {$name} with  ULID as {$tenant->ulid} having secret {$tenant->secret} ");
})->purpose('Creating a new Tenant => name');;

Artisan::command('splitpay:create-pg {name} {code}', function (string $name, string $code) {
    $pg = new Paymentgateway;
      
    $pg->name = $name;
    $pg->code = $code;

    $pg->save();

    $this->info("Created a new Payment Gateway {$name} with code as {$code} ");
})->purpose('Creating a new Payment Gateway => name | code');;


Artisan::command('splitpay:create-submerchant {tenantid} {pgid} {name}', function (string $tenantid, string $pgid, string $name) {
    $smc = new Submerchant;

    $tenant = Tenant::find($tenantid);
    $pg = Paymentgateway::find($pgid);
      
    $smc->tenant_id = $tenantid;
    $smc->paymentgateway_id = $pgid;
    $smc->dba_name = $name;
    $smc->status = 'inactive';

    $smc->save();

    $this->info("Created a new sub-Merchant {name} under tenant {$tenant->name} with payment gateway {$pg->name} ");
})->purpose('Creating a new sub-Merchant => tenantid | pgid | name ');


Artisan::command('splitpay:update-submerchant-bankdetails {submerchantid} {bankname} {ifsc} {accounttype} {accountnumber}', function (string $submerchantid, string $bankname, string $ifsc, string $accounttype, string $accountnumber) {

    $smc = Submerchant::find($submerchantid);
      
    if(!$smc) { 
      $this->info("No sub-Merchant with {$submerchantid} ");
    }
    else {  
      $smc->bank_name = $bankname;
      $smc->ifsc = $ifsc;
      $smc->account_type = $accounttype;
      $smc->account_number = $accountnumber;

      $smc->save();
      $this->info("Updated sub-Merchant {$smc->dbaname} ");
    }

})->purpose('Update a sub-Merchant => submerchantid | bankname | ifsc | accounttype | accountnumber ');


Artisan::command('splitpay:update-submerchant-gstn {submerchantid} {gstn}', function (string $submerchantid, string $gstn) {

    $smc = Submerchant::find($submerchantid);
    if(!$smc) { 
      $this->info("No sub-Merchant with {$submerchantid} ");
    }
    else {  
      $smc->gstn = $gstn;

      $smc->save();

      $this->info("Updated sub-Merchant {$smc->dba_name} ");
    }

})->purpose('Update a sub-Merchant => submerchantid | gstn ');


Artisan::command('splitpay:deactivate-submerchant {submerchantid}', function (string $submerchantid) {

    $smc = Submerchant::find($submerchantid);
    if(!$smc) { 
      $this->info("No sub-Merchant with {$submerchantid} ");
    }
    else {  
      $smc->status = 'inactive';

      $smc->save();

      $this->info("Deactivated sub-Merchant {$smc->dba_name} ");
    }

})->purpose('Deactivate status of a sub-Merchant => submerchantid');

Artisan::command('splitpay:activate-submerchant {submerchantid}', function (string $submerchantid) {

    $smc = Submerchant::find($submerchantid);
    if(!$smc) { 
      $this->info("No sub-Merchant with {$submerchantid} ");
    }
    else {  
      $smc->status = 'active';

      $smc->save();

      $this->info("Activated sub-Merchant {$smc->dba_name} ");
    }

})->purpose('Activate status of a sub-Merchant => submerchantid');
