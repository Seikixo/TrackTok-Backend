<?php

namespace App\Repositories;

use App\Models\Service;

class ServiceRepository
{
    public function getServices(array $params = [])
    {
        $query = Service::query()
            ->with('category');

        if (!empty($params['search'])) {
            $query->where('name', 'like', '%' . $params['search'] . '%');
        }

        $sortable = ['name', 'price', 'duration', 'created_at'];
        if (!empty($params['sort_by']) && in_array($params['sort_by'], $sortable)) {
            $order = in_array($params['sort_order'] ?? 'asc', ['asc', 'desc']) ? $params['sort_order'] : 'asc';
            $query->orderBy($params['sort_by'], $order);
        }

        if (!empty($params['per_page'])) {
            return $query->paginate($params['per_page'] ?? 10);
        }

        return $query->get();
    }

    public function createService(array $data)
    {
        return Service::create($data);
    }

    public function updateService($id, array $data)
    {
        $service = Service::findOrFail($id);
        $service->update($data);

        return $service;
    }

    public function deleteService($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return $service;
    }
}