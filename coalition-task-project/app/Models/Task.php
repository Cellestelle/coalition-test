<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Mass-assignable fields
    protected $fillable = [
        'name',
        'project_id',
        'priority'
    ];

    // Relationship: A task belongs to a project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
