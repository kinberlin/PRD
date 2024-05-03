<?php

namespace App\Livewire;

use App\Models\Enterprise;
use App\Models\Site;
use Livewire\Component;

class SiteRenderer extends Component
{
    public $data = [];
    public $ents = [];

    public function mount()
    {
        $this->fill([
            'data' => Site::all(),
            'ents' => Enterprise::all()
        ]);
    }
    public function render()
    {
        return view('livewire.admin.site-renderer');
    }
}
