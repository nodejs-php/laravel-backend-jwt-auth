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
    protected $casts = [
        'deadline' => 'datetime:Y-m-d',
    ];


    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, "project_id", "id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function toArray(array $fields = []): array
    {
        $original = parent::toArray();

        if (empty($fields)) {
            return $original;
        }


        return array_intersect_key($original, array_flip($fields));
    }
}
