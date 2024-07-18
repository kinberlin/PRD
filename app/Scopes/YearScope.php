<?php
// app/Scopes/YearScope.php
namespace App\Scopes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class YearScope implements Scope
{
    protected $year;

    public function __construct( $year = 2024 )
    {
        $this->year = $year;
    }

    public function apply(Builder $builder, Model $model)
    {
        $builder->whereYear('created_at', $this->year);
    }
}
