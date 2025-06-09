<?php

namespace App\Repositories;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class AppointmentRepository
{

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

        $sortable = ['appointment_date', 'status', 'created_at'];
        if (!empty($params['sort_by']) && in_array($params['sort_by'], $sortable))
        {
            $order = in_array($params['sort_order'] ?? 'asc', ['asc', 'desc']) ? $params['sort_order'] : 'asc';
            $query->orderBy($params['sort_by'], $order);
        }

        if (!empty($params['per_page']))
        {
            return $query->paginate($params['per_page'] ?? 10);
        }

        return $query->get();
    }

    public function createAppointment(array $data)
    {
        // Extract services and remove them from main data
        $services = $data['services'];
        unset($data['services']);

        // Create the appointment
        $appointment = Appointment::create($data);

        // Prepare data for pivot table
        $pivotData = [];

        foreach ($services as $service) {
            $pivotData[$service['service_id']] = [
                'service_quantity' => $service['service_quantity'],
                'total_price_at_appointment' => $service['total_price_at_appointment'],
            ];
        }

        // Attach services with pivot data
        $appointment->services()->attach($pivotData);

        return $appointment;
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
