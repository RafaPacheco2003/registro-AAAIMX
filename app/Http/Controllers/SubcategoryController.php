<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use App\Services\SubcategoryService;
use App\Http\Requests\SubcategoryRequest;
use App\Http\Resources\SubcategoryResource;

class SubcategoryController extends Controller
{
    protected $service;

    public function __construct(SubcategoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $subcategories = SubcategoryResource::collection($this->service->getAll());
        return $this->success($subcategories, 'Subcategorias obtenidas exitosamente');
    }

    public function store(SubcategoryRequest $request)
    {
        $subcategory = $this->service->create($request->validated());
        return $this->success(new SubcategoryResource($subcategory), 'Subcategoria creada exitosamente', 201);
    }

    public function show(Subcategory $subcategory)
    {
        return $this->success(new SubcategoryResource($subcategory->load('category')), 'Subcategoria obtenida exitosamente');
    }

    public function update(SubcategoryRequest $request, Subcategory $subcategory)
    {
        $subcategory = $this->service->update($subcategory, $request->validated());
        return $this->success(new SubcategoryResource($subcategory), 'Subcategoria actualizada exitosamente');
    }

    public function destroy(Subcategory $subcategory)
    {
        $this->service->delete($subcategory);
        return $this->success(null, 'Subcategoria eliminada exitosamente');
    }
}
