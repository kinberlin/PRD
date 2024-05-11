<?php
namespace App\Models;

use Carbon\Carbon;

class Viewby
{
    public $user;
    public $department;
    public $view_at = null;

    public function __construct( $user, $department)
    {
        $this->department = $department;
        $this->user = $user;
        $this->view_at = Carbon::now();
    }
}
