<?php
namespace App\Models;

class Correction
{
    public $action;
    public $department;
    public $user;
    public $end_date;

    public function __construct($action, $department, $user,  $end_date)
    {
        $this->action = $action;
        $this->department = $department;
        $this->user = $user;
        $this->end_date = $end_date;
    }
}
