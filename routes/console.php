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

Artisan::command('splitpay:create-tenant {name} {code}', function (string $name, string $code) {
    $tenant = new Tenant;
      
    $tenant->uuid = Str::ulid()->toRfc4122();
    $tenant->name = $name;
    $tenant->code = $code;
    $tenant->secret = (string) Str::ulid();
    $tenant->status = 'inactive';

    $tenant->save();

    $this->info("Created a new Tenant {$name} having code {$code} with  UUID as {$tenant->uuid} having secret {$tenant->secret} ");
})->purpose('Creating a new Tenant => name | code');;

Artisan::command('splitpay:create-pg {name} {merchantid} {clientid} {clientsecret}', function (string $name, string $merchantid, string $clientid, string $clientsecret) {
    $pg = new Paymentgateway;
      
    $pg->name = $name;
    $pg->status = 'active';
    $pg->merchantid = $merchantid;
    $pg->clientid = $clientid;
    $pg->clientsecret = $clientsecret;

    $pg->save();

    $this->info("Created a new Payment Gateway {$name}");
})->purpose('Creating a new Payment Gateway => name | merchantid | clientid | clientsecret');


Artisan::command('splitpay:create-submerchant {tenantcode} {pgid} {name} {refno} {code}', function (string $tenantcode, string $pgid, string $name, string $refno, string $code) {
    $smc = new Submerchant;

    $tenant = Tenant::where('code',$tenantcode)->first();
    $pg = Paymentgateway::find($pgid);
      
    $smc->tenant_id = $tenant->id;
    $smc->paymentgateway_id = $pgid;
    $smc->code = $code;
    $smc->dba_name = $name;
    $smc->externaltenantreference = $refno;
    $smc->status = 'inactive';

    $smc->save();

    $this->info("Created a new sub-Merchant {$name} having code {$code} under tenant {$tenant->name} with payment gateway {$pg->name} ");
})->purpose('Creating a new sub-Merchant => tenantuuid | pgid | name | submerchant reference number | code');


Artisan::command('splitpay:update-submerchant-bankdetails {submerchantcode} {bankname} {ifsc} {accounttype} {accountnumber}', function (string $submerchantcode, string $bankname, string $ifsc, string $accounttype, string $accountnumber) {

    $smc = Submerchant::where('code',$submerchantcode)->first();
      
    if(!$smc) { 
      $this->info("No sub-Merchant with code as {$submerchantcode} ");
    }
    else {  
      $smc->bank_name = $bankname;
      $smc->ifsc = $ifsc;
      $smc->account_type = $accounttype;
      $smc->account_number = $accountnumber;

      $smc->save();
      $this->info("Updated sub-Merchant {$smc->dbaname} ");
    }

})->purpose('Update a sub-Merchant => submerchantcode | bankname | ifsc | accounttype | accountnumber ');


Artisan::command('splitpay:update-submerchant-gstn {submerchantcode} {gstn}', function (string $submerchantcode, string $gstn) {

    $smc = Submerchant::where('code',$submerchantcode);
    if(!$smc) { 
      $this->info("No sub-Merchant with code as {$submerchantcode} ");
    }
    else {  
      $smc->gstn = $gstn;

      $smc->save();

      $this->info("Updated sub-Merchant {$smc->dba_name} ");
    }

})->purpose('Update a sub-Merchant => submerchantcode | gstn ');


Artisan::command('splitpay:deactivate-submerchant {submerchantcode}', function (string $submerchantcode) {

    $smc = Submerchant::where('code',$submerchantcode);
    if(!$smc) { 
      $this->info("No sub-Merchant with code as {$submerchantcode} ");
    }
    else {  
      $smc->status = 'inactive';

      $smc->save();

      $this->info("Deactivated sub-Merchant {$smc->dba_name} ");
    }

})->purpose('Deactivate status of a sub-Merchant => submerchantcode');

Artisan::command('splitpay:activate-submerchant {submerchantcode}', function (string $submerchantcode) {

    $smc = Submerchant::where('code',$submerchantcode);
    if(!$smc) { 
      $this->info("No sub-Merchant with code as {$submerchantcode} ");
    }
    else {  
      $smc->status = 'active';

      $smc->save();

      $this->info("Activated sub-Merchant {$smc->dba_name} ");
    }

})->purpose('Activate status of a sub-Merchant => submerchantcode');


Artisan::command('splitpay:activate-tenant {tenantcode}', function (string $tenantcode) {
    $tenant = Tenant::where('code',$tenantcode);
    if(!$tenant) { 
      $this->info("No Tenant with code as {$tenantcode} ");
    }
    else {  
      $tenant->status = 'active';

      $tenant->save();

      $this->info("Activated Tenant {$tenant->name} ");
    }
})->purpose('Activate status of a Tenant => tenantcode');


Artisan::command('splitpay:list-tenants', function () {
  $this->table(
    ['Name', 'UUID', 'Code', 'Status'],
    Tenant::all(['name', 'uuid', 'code' ,'status'])->toArray()
  );
})->purpose('List of all Tenants with Code and UUID and Name');


Artisan::command('splitpay:list-submerchants {tenantcode}', function (string $tenantcode) {
    $tenant = Tenant::find($tenantcode);
    if(!$tenant) { 
      $this->info("No Tenant with code as {$tenantcode} ");
    }
    else
    {
      $this->table(
        ['Name', 'Status'],
        Tenant::all(['name', 'uuid', 'code' ,'status'])->where('tenant_id',$tenant->id)->toArray()
      );
    }
})->purpose('List of all Merchants for a given Tenant');
