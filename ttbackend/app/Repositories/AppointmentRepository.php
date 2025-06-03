<?php

namespace App\Repositories;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class AppointmentRepository
{

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

    public function getAppoitments(array $params = [])
    {
        $query = Appointment::query();

        if (!empty($params['search']))
        {
            $query->whereRelation('customer', 'name', 'like', '%' . $params['search'] . '%');
        }

        if (!empty($params['date']))
        {
            $query->whereDate('appointment_date', $params['date']);
        }

        if (!empty($params['status']))
        {
            $query->where('status', $params['status']);
        }

        if (!empty($params['sort_by']))
        {
            $query->orderBy($params['sort_by'], $params['sort_order'] ?? 'asc');
        }

        if (!empty($params['per_page']))
        {
            return $query->paginate($params['per_page'] ?? 10);
        }

        return $query->get();
    }
}
