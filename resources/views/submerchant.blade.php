
<x-app-layout>
    <x-slot name="header">
      <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
        <div class="flex flex-shrink-0 items-center">
<a href="/submerchants">
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
</svg>
</a>
        </div>
        <div class="hidden sm:ml-6 sm:block">
SUB MERCHANT
        </div>
        <div class="hidden sm:ml-6 sm:block">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $submerchant->dba_name }}
        </h1>
        </div>
      </div>
    </x-slot>

    <div class="py-12">

 <div class="container mx-auto">
    <table class="min-w-min">
      <tbody>
        <!-- Row 1 -->
        <tr>
          <td class="py-2 px-4 border-b font-semibold">Submerchant ID</td>
          <td class="py-2 px-4 border-b">{{$submerchant->id}}</td>
        </tr>

        <!-- Row 2 -->
        <tr>
          <td class="py-2 px-4 border-b font-semibold">Tenant ID / Name</td>
          <td class="py-2 px-4 border-b">{{$submerchant->tenant_id}} / {{$submerchant->tenant->name}}</td>
        </tr>

        <!-- Row 3 -->
        <tr>
          <td class="py-2 px-4 border-b font-semibold">Payment Gateway ID / Name</td>
          <td class="py-2 px-4 border-b">{{$submerchant->paymentgateway_id}} / {{$submerchant->paymentgateway->name}}</td>
        </tr>

        <!-- Row 4 -->
        <tr>
          <td class="py-2 px-4 border-b font-semibold">MID from PG</td>
          <td class="py-2 px-4 border-b">{{$submerchant->mid}}</td>
        </tr>

        <!-- Row 5 -->
        <tr>
          <td class="py-2 px-4 border-b font-semibold">TID from PG</td>
          <td class="py-2 px-4 border-b">{{$submerchant->tid}}</td>
        </tr>

        <!-- Row 6 -->
        <tr>
          <td class="py-2 px-4 border-b font-semibold">Unique Submerchant ID at Tenant</td>
          <td class="py-2 px-4 border-b">{{$submerchant->tenantsubmerchantid}}</td>
        </tr>

        <!-- Row 7 -->
        <tr>
          <td class="py-2 px-4 border-b font-semibold">Doing Business As Name</td>
          <td class="py-2 px-4 border-b">{{$submerchant->dba_name}}</td>
        </tr>

        <!-- Row 8 -->
        <tr>
          <td class="py-2 px-4 border-b font-semibold">GSTN</td>
          <td class="py-2 px-4 border-b">{{$submerchant->gstn}}</td>
        </tr>

        <!-- Row 9 -->
        <tr>
          <td class="py-2 px-4 border-b font-semibold">Bank Name</td>
          <td class="py-2 px-4 border-b">{{$submerchant->bank_name}}</td>
        </tr>

        <!-- Row 10 -->
        <tr>
          <td class="py-2 px-4 border-b font-semibold">IFSC</td>
          <td class="py-2 px-4 border-b">{{$submerchant->ifsc}}</td>
        </tr>

        <!-- Row 11 -->
        <tr>
          <td class="py-2 px-4 border-b font-semibold">Account Type</td>
          <td class="py-2 px-4 border-b">{{$submerchant->account_type}}</td>
        </tr>

        <!-- Row 12 -->
        <tr>
          <td class="py-2 px-4 border-b font-semibold">Account Number</td>
          <td class="py-2 px-4 border-b">{{$submerchant->account_number}}</td>
        </tr>

        <!-- Row 13 -->
        <tr>
          <td class="py-2 px-4 border-b font-semibold">Status</td>
          <td class="py-2 px-4 border-b">{{$submerchant->status}}</td>
        </tr>

        <!-- Row 14 -->
        <tr>
          <td class="py-2 px-4 border-b font-semibold">Created At</td>
          <td class="py-2 px-4 border-b">{{$submerchant->created_at}}</td>
        </tr>

        <!-- Row 15 -->
        <tr>
          <td class="py-2 px-4 border-b font-semibold">Updated At</td>
          <td class="py-2 px-4 border-b">{{$submerchant->updated_at}}</td>
        </tr>
      </tbody>
    </table>
  </div>


    </div>
</x-app-layout>
