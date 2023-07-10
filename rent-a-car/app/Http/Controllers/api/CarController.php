<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Services\CarService;
use App\Models\Car;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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
    public function index(Request $request)
    {
        $type = $request->input('type');
        $model = $request->input('model');
        $year = $request->input('year');
        $pricePerDay = $request->input('price_per_day');

        $query = Car::query();
        if ($type) {
            $query->where('type', 'like', "%$type%");
        }

        if ($model) {
            $query->where('model', 'like', "%$model%");
        }

        if ($year) {
            $query->where('year', $year);
        }

        if ($pricePerDay) {
            $query->where('price_per_day', $pricePerDay);
        }

        $cars = $query->get();

        return response()->json(['data' => $cars], ResponseAlias::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarRequest $request)
    {
        return $this->carService->store($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCarRequest $request, $id)
    {
        $updatedCar = $this->carService->update($request->validated(), $id);
        if(!$updatedCar) {
            return response(['message' => 'Car not found'], ResponseAlias::HTTP_NOT_FOUND);
        }

        return $updatedCar;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deletedCar = $this->carService->delete($id);
        if(!$deletedCar){
            return response(['message' => 'Car not found'], ResponseAlias::HTTP_NOT_FOUND);
        }

        return response(['message' => 'Successfully deleted'], ResponseAlias::HTTP_OK);
    }
}
