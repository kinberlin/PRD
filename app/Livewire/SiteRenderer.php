<?php

namespace App\Livewire;


use Livewire\Attributes\Validate;
use App\Models\Enterprise;
use App\Models\Site;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class SiteRenderer extends Component
{
    public $data = [];
    public $ents = [];
    public $location = "";
    public $name = "";
    public $enterprise = "";
    public $text = 'big';

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
            if (Gate::allows('isEnterpriseRQ', [Enterprise::find($this->enterprise)]) || Gate::allows('isAdmin', Auth::user())) {
                $site = new Site();
                $site->name = $this->name;
                $site->enterprise = $this->enterprise;
                $site->location = $this->location;
                $site->save();
                // $this->emit('saved');

                session()->flash('error', "Insertions terminées avec succès");
                $this->dispatch('message'); 
            } else {
                throw new Exception("Arrêt inattendu du processus suite a une tentative d'insertion/de manipulation de donnée sans detention des privileges requis pour l'operation.", 501);
            }
        } catch (\Exception $e) {
            $this->dispatch('message'); 
            session()->flash('errors', 'Erreur : ' . $e->getMessage());
        }
    }
    public function render()
    {
        $this->data = Site::all();
        $this->ents = Enterprise::all();
        return view('livewire.admin.site-renderer');
    }
}
