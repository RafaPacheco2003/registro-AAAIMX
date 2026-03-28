<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubcategoryRequest;
use Illuminate\Http\Request;
use App\Models\Subcategory;

class SubcategoryController extends Controller
{
    public function index()
    {
        return response()->json(Subcategory::with('category')->get());
    }

    public function store(SubcategoryRequest $request)
    {
        $sub = Subcategory::create($request->validated());
        return response()->json($sub, 201);
    }

    public function show(Subcategory $subcategory)
    {
        return response()->json($subcategory->load('category'));
    }

    public function update(SubcategoryRequest $request, Subcategory $subcategory)
    {
        $subcategory->update($request->validated());
        return response()->json($subcategory);
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();
        return response()->json(null, 204);
    }
}
