<?php
namespace App\Models;

use Carbon\Carbon;

class Viewby
{
    public $user;
    public $view_at = null;

    public function __construct($matricule)
    {
        $this->user = $matricule;
        $this->view_at = Carbon::now();
    }
}
