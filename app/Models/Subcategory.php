<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $fillable = ['name', 'project', 'price', 'category_id'];

    protected function casts(): array
    {
        return [
            'project' => 'integer',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function registers()
    {
        return $this->hasMany(Register::class);
    }
}
