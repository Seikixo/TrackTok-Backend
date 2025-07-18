<?php

namespace App\Repositories;

use App\Models\Organization;
use Illuminate\Support\Facades\Cache;

class OrganizationRepository
{
    public function getOrganizations(array $params)
    {
        $hasQueryModifiers = !empty($params['search'])
            || !empty($params['sort_by'])
            || !empty($params['sort_order'])
            || !empty($params['per_page']);

        if($hasQueryModifiers)
        {
            $query = Organization::query()
                ->with(['services', 'user']);

            if(!empty($params['search']))
            {
                $query->where('name', 'like', '%' . $params['search'] . '%' );
            }

            $sortable = ['name', 'created_at'];
            if(!empty($params['sort_by']) && in_array($params['sort_by'], $sortable))
            {
                $order = in_array($params['sort_order'] ?? 'asc', ['asc', 'desc']) ? $params['sort_order'] : 'asc';
                $query->orderBy($params['sort_by'], $order);
            }

            return !empty($params['per_page'])
                ? $query->paginate($params['per_page'] ?? 10)
                : $query->get();
        }

        return Cache::remember('all_organization', 60, function() {
            return Organization::with(['services', 'user'])->get();
        });
    }

    public function createOrganization(array $data)
    {
        Cache::forget('all_organization');
        return Organization::create($data);
    }

    public function updateOrganization($id, array $data)
    {
        $organization = Organization::findOrFail($id);
        $organization->update($data);
        Cache::forget('all_organization');
        return $organization;
    }

    public function deleteOrganization($id)
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();
        Cache::forget('all_organization');
        return $organization;
    }
}