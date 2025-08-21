<?php

namespace App\Livewire;

use App\Models\Insumo;
use App\Models\Trabajo;
use Livewire\Component;

class Verinsumo extends Component
{
    public $identificador;
    public function render()
    {
         $insumos = Insumo::where('trabajo_id', $this->identificador)->get();
          $trabajo = Trabajo::find($this->identificador);
        return view('livewire.verinsumo',compact('insumos','trabajo'));
    }
}
