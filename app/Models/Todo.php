<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = ['title', 'created_at', 'is_completed', 'priority', 'task', 'category_id'];
    protected $casts = [
        'is_completed' => 'boolean'
    ];
    
    /** @use HasFactory<\Database\Factories\TodoFactory> */
    use HasFactory;

    /**
     * A feladathoz tartozó kategória lekérése.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category() {
        return $this->belongsTo(Category::class);
    }
}
