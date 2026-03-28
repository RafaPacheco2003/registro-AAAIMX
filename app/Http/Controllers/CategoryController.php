<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Services\CategoryService;
use App\Http\Requests\CategoryRequest;


class CategoryController extends Controller
{
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->getAll();
    }

    public function store(CategoryRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function show(Category $category)
    {
        return $category;
    }

    public function update(CategoryRequest $request, Category $category)
    {
        return $this->service->update($category, $request->validated());
    }

    public function destroy(Category $category)
    {
        return $this->service->delete($category);
    }
}