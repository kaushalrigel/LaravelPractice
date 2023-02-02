<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employee';

    /**
     * Get the comments for the blog post.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    use SoftDeletes;
}
