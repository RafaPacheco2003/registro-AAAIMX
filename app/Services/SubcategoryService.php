<?php

namespace App\Services;

use App\Models\Subcategory;

class SubcategoryService
{
    public function getAll()
    {
        return Subcategory::with('category')->get();
    }

    public function create(array $data)
    {
        return Subcategory::create($data);
    }

    public function update(Subcategory $subcategory, array $data)
    {
        $subcategory->update($data);
        return $subcategory;
    }

    public function delete(Subcategory $subcategory)
    {
        return $subcategory->delete();
    }
}
