<?php

namespace App\Repositories;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class AppointmentRepository
{

    public function getAppoitments(array $params = [])
    {
        $query = Appointment::query();
        $query->with('customer');

        if (!empty($params['search']))
        {
            $query->whereRelation('customer', 'name', 'like', '%' . $params['search'] . '%');
        }

        if (!empty($params['sort_by']))
        {
            $query->orderBy($params['sort_by'], $params['sort_order'] ?? 'asc');
        }

        if (!empty($params['per_page']))
        {
            $query->paginate($params['per_page'] ?? 10);
        }

        return $query->get();
    }

    public function createAppointment(array $data)
    {
        return Appointment::create($data);
    }

    public function updateAppointment($id, array $data)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update($data);

        return $appointment;
    }

    public function deleteAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);

        return $appointment->delete();
    }
}
