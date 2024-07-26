<?php
// app/Scopes/YearScope.php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Session;

class YearScope implements Scope
{
    protected $year;

    public function __construct($year = 2024)
    {
        if ($year > 2000) {$this->year = $year;} else {
            Session::put('currentYear', now()->year);
            $this->year = now()->year;
        }
    }

    public function apply(Builder $builder, Model $model)
    {
        $builder->whereYear('created_at', $this->year);
    }
}
