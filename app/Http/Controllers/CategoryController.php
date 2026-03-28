<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryService;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $categories = CategoryResource::collection($this->service->getAll());
        return $this->success($categories, 'Categorias obtenidas exitosamente');
    }

    public function store(CategoryRequest $request)
    {
        $category = $this->service->create($request->validated());
        return $this->success(new CategoryResource($category), 'Categoria creada exitosamente', 201);
    }

    public function show(Category $category)
    {
        return $this->success(new CategoryResource($category->load('subcategories')), 'Categoria obtenida exitosamente');
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category = $this->service->update($category, $request->validated());
        return $this->success(new CategoryResource($category), 'Categoria actualizada exitosamente');
    }

    public function destroy(Category $category)
    {
        $this->service->delete($category);
        return $this->success(null, 'Categoria eliminada exitosamente');
    }
}