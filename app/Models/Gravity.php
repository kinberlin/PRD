<?php

namespace App\Models;

use App\Scopes\YearScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    $id
 * @property int    $created_at
 * @property int    $deleted_at
 * @property int    $least_price
 * @property int    $max_price
 * @property int    $note
 * @property string $name
 */
class Gravity extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gravity';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_at', 'deleted_at', 'least_price', 'max_price', 'name', 'note',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'int', 'created_at' => 'timestamp', 'deleted_at' => 'timestamp', 'least_price' => 'int', 'max_price' => 'int', 'name' => 'string', 'note' => 'int',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'deleted_at',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    // Scopes...

    // Functions ...
    protected static function booted()
    {
        static::addGlobalScope(new YearScope(session('currentYear')));
    }

    // Relations ...
    public function dysfunctions()
    {
        return $this->hasMany(Dysfunction::class, 'gravity_id', 'id');
    }
}
