<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Repositories\AppointmentRepository;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $appointments = $this->appointmentRepository->getAppoitments($request->all());

        return response()->json([
            'success' => true,
            'appointments' => $appointments
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AppointmentRequest $request)
    {
        $validatedData = $request->validated();

        $this->appointmentRepository->createAppointment([
            'customer_id' => $validatedData['customer_id'],
            'appointment_date' => $validatedData['appointment_date'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
            'total_price' => $validatedData['total_price'],
            'status' => $validatedData['status'],
            'notes' => $validatedData['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AppointmentRequest $request, $id)
    {
        $validatedData = $request->validated();

        $updatedAppointment = $this->appointmentRepository->updateAppointment($id, [
            'customer_id' => $validatedData['customer_id'],
            'appointment_date' => $validatedData['appointment_date'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
            'total_price' => $validatedData['total_price'],
            'status' => $validatedData['status'],
            'notes' => $validatedData['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment updated successfully',
            'updatedAppointment' => $updatedAppointment,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
