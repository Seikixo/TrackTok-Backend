<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function getCategories(array $params = [])
    {
        $query = Category::query()
            ->with('services');

        if (!empty($params['search'])) {
            $query->where('name', 'like', '%' . $params['search'] . '%');
        }

        if (!empty($params['sort_by'])) {
            $query->orderBy($params['sort_by'], $params['sort_order'] ?? 'asc');
        }

        if (!empty($params['per_page'])) {
            return $query->paginate($params['per_page'] ?? 10);
        }

        return $query->get();
    }

    public function createCategory(array $data)
    {
        return Category::create($data);
    }

    public function updateCategory($id, array $data)
    {
        $category = Category::findOrFail($id);
        $category->update($data);

        return $category;
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return $category;
    }
}
