<?php

namespace App\Livewire;

use Livewire\Component;

class AddpltEmployeeForm extends Component
{
    public $enterprises;
    public $authorisations;
    public $title = 'big';
    public $users;
    public $selectedEnterprise = null;
    public $selectedUser = null;
    public $isInterim = 1;
    public $disableNoRadio = false;
    public $disableYesRadio = false;
    public $message = null;
    public $disableSubmit = true;

    public function mount()
    {
        $this->enterprises = Enterprise::all();
        $this->authorisations = AuthorisationRq::all();
        $this->users = Users::all();
        $this->selectedEnterprise = $this->enterprises[0]->id;
        $this->selectedUser = $this->users[0]->id;
        $this->checkUserInEnterprise();
        $this->checkFormReady();
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
        $this->checkUserInEnterprise();
    }

    public function checkUserInEnterprise()
    {
        // Assuming a method to check if user belongs to enterprise
        $user = Users::find($this->selectedUser);
        if ($user != null) {
            $_auths = $this->authorisations->where('user', $user->id); //Oui : 1 Non : 0

            if (!blank($_auths->where('enterprise', $this->selectedEnterprise)->where('interim', $this->isInterim))) {
                if ($this->isInterim == 0) {
                    $this->disableNoRadio = true;
                    $this->disableYesRadio = false;
                    $this->message = 'M/Mme ' . $user->firstname . ' est présentement Responsable Qualité principale à ' . $this->enterprises->where('id', $this->selectedEnterprise)->first()->name;
                    $this->isInterim = 1;
                } else {
                    $this->disableNoRadio = false;
                    $this->disableYesRadio = true;
                    $this->message = 'M/Mme ' . $user->firstname . ' est présentement Responsable Qualité par intérim à ' . $this->enterprises->where('id', $this->selectedEnterprise)->first()->name;
                    $this->isInterim = 0;
                }
                $this->checkFormReady();
            }
        }
    }

    public function checkFormReady()
    {
        if (!is_null($this->selectedEnterprise) && !is_null($this->selectedUser) && !is_null($this->isInterim)) {
            $this->disableSubmit = false;
        } else {
            $this->disableSubmit = true;
        }
    }
    public function render()
    {
        return view('livewire.admin.addplt-employee-form');
    }
}
