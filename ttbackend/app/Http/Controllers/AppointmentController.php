<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Repositories\AppointmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $appointments = $this->appointmentRepository->getAppointments($request->all());

        return response()->json([
            'success' => true,
            'message' => $appointments->isEmpty() ? 'No appointments found.' : 'Appointment fetched successfully.',
            'appointments' => $appointments
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AppointmentRequest $request)
    {
        $validatedData = $request->validated();
        $this->appointmentRepository->createAppointment($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Appointment created successfully.',
        ], 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(AppointmentRequest $request, $id)
    {
        $validatedData = $request->validated();
        $updatedAppointment = $this->appointmentRepository->updateAppointment($id, $validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Appointment updated successfully.',
            'updatedAppointment' => $updatedAppointment,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->appointmentRepository->deleteAppointment($id);

        return response()->json([
            'status' => true,
            'message' => 'Appointment deleted successfully.'
        ], 200);
    }
}
