<?php

namespace App\Models;

use App\Scopes\YearScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    $id
 * @property int    $enterprise
 * @property int    $created_at
 * @property int    $deleted_at
 * @property string $name
 * @property int    $visible
 * @property string $location
 */
class Site extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'site';

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
        'name', 'enterprise', 'location', 'created_at', 'deleted_at', 'visible',
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
        'id' => 'int', 'visible' => 'boolean', 'name' => 'string', 'enterprise' => 'int', 'location' => 'string', 'created_at' => 'timestamp', 'deleted_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'deleted_at'
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
        return $this->hasMany(Dysfunction::class, 'site_id', 'id');
    }
    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class, 'enterprise');
    }
}
