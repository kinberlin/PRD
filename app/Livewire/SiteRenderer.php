<?php

namespace App\Livewire;


use Livewire\Attributes\Validate; 
use App\Models\Enterprise;
use App\Models\Site;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SiteRenderer extends Component
{
    public $data = [];
    public $ents = [];
    public $location ="";
    public $name=""; 
    public $enterprise="";

    public function mount()
    {
        $this->fill([
            'data' => Site::all(),
            'ents' => Enterprise::all(),
        ]);
    }
    public function save()
    {
        try {
            $site = new Site();
            $site->name = $this->name;
            $site->enterprise = $this->enterprise;
            $site->location = $this->location;
            $site->save();
             // $this->emit('saved');

            session()->flash('error', "Insertions terminées avec succès");
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', "Erreur : " . $e->getMessage());
        }

    }
    public function render()
    {
        $this->data = Site::all();
        $this->ents = Enterprise::all();
        return view('livewire.admin.site-renderer');
    }
}
