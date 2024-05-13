<?php
namespace App\Models;

use Carbon\Carbon;

class Invites
{
    public $email;
    public $department;
    public $enterprise;
    public $matricule;
    public $created_at;
    public $reasons;
    public $decision;
    public $deleted_at = null;

    public function __construct(Users $user)
    {
        $this->email = $user->email;
        $this->department = $user->department;
        $this->enterprise = $user->enterprise;
        $this->matricule = $user->matricule;
        $this->decision = 'En attente de Validation';
        $this->created_at = Carbon::now();
    }

    public function confirm(){
        $this->decision =  'Présence Confirmer';
        $this->reasons = 'Valider';
        return $this;
    }
    public function cancel($reason){
        $this->decision =  'Rejeté';
        $this->reasons = $reason;
        return $this;
    }
    public function deleted(){
        $this->deleted_at =  Carbon::now();
        return $this;
    }
}