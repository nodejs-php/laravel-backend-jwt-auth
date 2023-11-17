<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    // use CascadeSoftDeletes;
    use HasFactory;

    // protected $cascadeDeletes = ['tasks'];

    protected $fillable = [
        "project_title",
        "user_id",
        "description",
        "deadline",
        "status",
        "priority"
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, "project_id", "id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
