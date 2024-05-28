<?php

namespace App\Livewire;

use App\Models\AuthorisationRq;
use Livewire\Component;
use App\Models\Enterprise;
use App\Models\Users;

class AddRQEmployeeForm extends Component
{
    public $enterprises;
    public $authorisations;
    public $title = 'big';
    public $users;
    public $selectedEnterprise = '';
    public $selectedUser = '';
    public $isInterim = null;
    public $disableNoRadio = false;
    public $disableYesRadio = false;
    public $message = null;
    public $disableSubmit = true;

    public function mount()
    {
        $this->enterprises = Enterprise::all();
        $this->authorisations = AuthorisationRq::all();
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
        if($user){
            $_auths = $this->authorisations::where('user', $user->id);//Oui : 1 Non : 0
            if(!empty($_auths::where('enterprise',$this->selectedEnterprise )->where('interim',0)) ){
                $this->disableNoRadio = true;
                $this->disableYesRadio = false;
                $message = 'M/Mme '.$user->firstname .' est présentement RQ principale à '.$this->enterprises::where()
            }
            if(!empty($_auths::where('enterprise',$this->selectedEnterprise )->where('interim',1)) ){
                $this->disableNoRadio = false;
                $this->disableYesRadio = true;
            }
        if ($user && $user->enterprise == $this->selectedEnterprise) {
            $this->disableNoRadio = true;
        } else {
            $this->disableNoRadio = false;
        }
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
