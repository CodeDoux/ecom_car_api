<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Models\Car;
use App\Services\CarService;
use Illuminate\Support\Facades\Log;


use Illuminate\Http\Request;

class CarController extends Controller
{
    protected $carService;
    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->carService->getAllCars();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarRequest $request)
{
    try {
        $data           = $request->validated();
        $data['images'] = $request->file('images') ?? [];

        $car = $this->carService->createCar($data);
        return response()->json($car, 201);

    } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'line'    => $e->getLine(),
            'file'    => $e->getFile(),
        ], 500);
    }
}

    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->carService->getCarById($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarRequest $request, Car $car)
{
    try {
        \Log::info('Request all:', $request->all());
        \Log::info('Request validated:', $request->validated());

        $data           = $request->validated();
        $data['images'] = $request->file('images') ?? [];

        $car = $this->carService->updateCar($car, $data);
        return response()->json($car);

    } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'line'    => $e->getLine(),
            'file'    => $e->getFile(),
        ], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        $this->carService->deleteCar($car);
        return response()->json(['message' => 'Car deleted successfully']);
    }
}
