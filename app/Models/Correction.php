<?php
namespace App\Models;

class Corrections
{
    public $action;
    public $surname;
    public $created;

    public function __construct($name, $surname, $created)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->created = $created;
    }
}
?>