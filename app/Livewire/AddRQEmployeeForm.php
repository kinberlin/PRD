<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Enterprise;
use App\Models\Users;

class AddRQEmployeeForm extends Component
{
    public $enterprises;
    public $text = 'big';
    public $users;
    public $selectedEnterprise = '';
    public $selectedUser = '';
    public $isInterim = null;
    public $disableNoRadio = false;
    public $disableSubmit = true;

    public function mount()
    {
        $this->enterprises = Enterprise::all();
        $this->users = Users::all();
    }

    public function updatedSelectedEnterprise()
    {
        $this->checkUserInEnterprise();
    }

    public function updatedSelectedUser()
    {
        $this->checkUserInEnterprise();
    }

    public function updatedIsInterim()
    {
        $this->checkFormReady();
    }

    public function checkUserInEnterprise()
    {
        // Assuming a method to check if user belongs to enterprise
        $user = Users::where('email', $this->selectedUser)->first();
        if ($user && $user->enterprise == $this->selectedEnterprise) {
            $this->disableNoRadio = true;
        } else {
            $this->disableNoRadio = false;
        }

        $this->checkFormReady();
    }

    public function checkFormReady()
    {
        if ($this->selectedEnterprise && $this->selectedUser && !is_null($this->isInterim)) {
            $this->disableSubmit = false;
        } else {
            $this->disableSubmit = true;
        }
    }

    public function render()
    {
        return view('livewire.add-r-q-employee-form');
    }
}
