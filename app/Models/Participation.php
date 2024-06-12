<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class Participation implements Arrayable
{
    public $matricule;
    public $names;
    public $marked_by;
    public $marked_matricule;
    public $created_at;

    public function __construct(array $attributes = [])
    {
        $this->matricule = $attributes['matricule'] ?? null;
        $this->names = $attributes['names'] ?? null;
        $this->marked_by = $attributes['marked_by'] ?? null;
        $this->marked_matricule = $attributes['marked_matricule'] ?? null;
        $this->created_at = $attributes['created_at'] ?? Carbon::now();
    }

    public function toArray()
    {
        return [
            'matricule' => $this->matricule,
            'names' => $this->names,
            'marked_by' => $this->marked_by,
            'marked_matricule' => $this->marked_matricule,
            'created_at' => $this->created_at,
        ];
    }
}
