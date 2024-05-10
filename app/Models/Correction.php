<?php
namespace App\Models;

use Carbon\Carbon;

class Correction
{
    public $action;
    public $department;
    public $user;
    public $end_date = null;
    public $created_by;
    public $created_at;
    public $view_at = null;
    public $deleted_at = null;

    public function __construct($action, $department, $user,  $end_date, $created_by)
    {
        $this->action = $action;
        $this->department = $department;
        $this->user = $user;
        $this->end_date = $end_date;
        $this->created_by = $created_by;
        $this->created_at = Carbon::now();
    }

    public function setViewAt(){
        $this->view_at =  Carbon::now();
    }
}
