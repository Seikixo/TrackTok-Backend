<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Support\Facades\DB;

class AppointmentRepository
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function getAppointments(array $params = [])
    {
        $query = Appointment::query()
            ->with(['services', 'customer']);

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
        $services = $data['services'] ?? null;
        unset($data['services']);

        $appointment = Appointment::create($data);

        $pivotData = [];

        foreach ($services as $service) {
            $pivotData[$service['service_id']] = [
                'service_quantity' => $service['service_quantity'],
                'total_price_of_services' => $this->appointmentService->calculateTotalPriceOfService($service),
            ];
        }

        // Attach services with pivot data
        $appointment->services()->attach($pivotData);

        return $appointment;
    }                                                       


    public function updateAppointment($id, array $data)
    {
        $services = $data['services'] ?? null;
        unset($data['services']);

        $appointment = Appointment::findOrFail($id);
        $appointment->update($data);

        $pivotData = [];
        foreach ($services as $service) {
            $pivotData[$service['service_id']] = [
                'service_quantity' => $service['service_quantity'],
                'total_price_of_services' => $this->appointmentService->calculateTotalPriceOfService($service)
            ];
        }

        $appointment->services()->sync($pivotData);

        return $appointment;
    }

    public function deleteAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);

        return $appointment->delete();
    }


}
