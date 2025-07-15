<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryRepository
{

    public function getCategories(array $params = [])
    {
        $hasQueryModifiers = !empty($params['search']) || !empty($params['sort_by']) || !empty($params['per_page']);
        if($hasQueryModifiers)
        {
            logger('Fetching categories from DB due to search/sort/pagination.');
            $query = Category::query()
                ->with('services');

            if (!empty($params['search'])) {
                $query->where('name', 'like', '%' . $params['search'] . '%');
            }

            $sortable = ['name', 'created_at'];
            if (!empty($params['sort_by']) && in_array($params['sort_by'], $sortable)) {
                $order = in_array($params['sort_order'] ?? 'asc', ['asc', 'desc']) ? $params['sort_order'] : 'asc';
                $query->orderBy($params['sort_by'], $order);
            }

            return !empty($params['per_page'])
                ? $query->paginate($params['per_page']) 
                : $query->get();

        }
        logger('Fetching categories from cache.');       
        return Cache::remember('categories_with_services', 3600, function() {
            logger('Storing categories into cache (no search/sort).');
            return Category::with('services')->get();
        });
    }

    public function createCategory(array $data)
    {
        Cache::forget('categories_with_services');
        return Category::create($data);
    }

    public function updateCategory($id, array $data)
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        Cache::forget('categories_with_services');
        return $category;
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        Cache::forget('categories_with_services');
        return $category;
    }
}
