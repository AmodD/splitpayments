<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class IndexSubmerchant extends Component
{
    public function render()
    {
        return view('livewire.index-submerchant')->with([
          'submerchants' => Auth::user()->tenant->submerchants,
        ]);
    }
}
