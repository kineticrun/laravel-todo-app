<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['id', 'name'];

    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    public function todo() {
        return $this->hasMany(Todo::class);
    }
}
