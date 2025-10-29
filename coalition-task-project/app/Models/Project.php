<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // Mass-assignable fields
    protected $fillable = [
        'name'
    ];

    // Relatiopnship: A project has many tasks
    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('priority');
    }
}
