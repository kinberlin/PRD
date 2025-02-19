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

    public function __construct(Users $user = null, $exist = null)
    {
        if ($user != null) {
            $this->email = $user->email;
            $this->department = $user->department;
            $this->enterprise = $user->enterprise;
            $this->matricule = $user->matricule;
            $this->decision = 'En attente de Validation';
            $this->created_at = Carbon::now();
        } else {
            $this->email = data_get($exist, 'email');
            $this->department = data_get($exist, 'department');
            $this->enterprise = data_get($exist, 'enterprise');
            $this->matricule = data_get($exist, 'matricule');
            $this->decision = data_get($exist, 'decision');
            $this->created_at = data_get($exist, 'created_at');
        }
    }

    public function confirm()
    {
        $this->decision =  'Confirmer';
        $this->reasons = 'Je confirme ma présence';
        return $this;
    }
    public function cancel($reason = "Je décline l'invitation, je ne pourrai pas être là.")
    {
        $this->decision =  'Rejeté';
        $this->reasons = $reason;
        return $this;
    }
    public function deleted()
    {
        $this->deleted_at =  Carbon::now();
        return $this;
    }
}
