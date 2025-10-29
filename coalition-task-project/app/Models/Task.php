<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'project_id', 'priority'];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
