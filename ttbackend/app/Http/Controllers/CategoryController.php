<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = $this->categoryRepository->getCategories($request->all());

        return response()->json([
            'success' => true,
            'message' => $categories->isEmpty() ? 'No categories found.' : 'Categories fetched successfully.',
            'categories' => $categories
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $validatedData = $request->validated();
        
        $this->categoryRepository->createCategory([
            'name' => $validatedData['name'],
            'description' => $validatedData['description']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, $id)
    {
        $validatedData = $request->validated();

        $updatedCategory = $this->categoryRepository->updateCategory($id, [
            'name' => $validatedData['name'],
            'description' => $validatedData['description']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully.',
            'category' => $updatedCategory
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deletedCategory = $this->categoryRepository->deleteCategory($id);

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully.',
            'category' => $deletedCategory
        ], 200);
    }
}
