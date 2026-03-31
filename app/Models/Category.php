<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'project'];

    protected function casts(): array
    {
        return [
            'project' => 'integer',
        ];
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function registers()
    {
        return $this->hasMany(Register::class);
    }
}
