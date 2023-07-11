<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Services\CarService;
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
        $cars = $this->carService->searchCarByCriteria($request);
        if(!$cars) {
            return response(['message' => 'No cars with given search criteria'], ResponseAlias::HTTP_BAD_REQUEST);
        }
        return $cars;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarRequest $request)
    {
        $photoName = $request->file('photo')->getClientOriginalName();
        $documentName = $request->file('document')->getClientOriginalName();
        $photoPath = "storage/" . $request->file('photo')
                ->store('car-photos');
        $documentPath = "storage/" . $request->file('document')
                ->store('car-documents');

        $carData = $request->validated();
        $carData['photo'] = $photoPath;
        $carData['document'] = $documentPath;
        $carData['photo_name'] = $photoName;
        $carData['document_name'] = $documentName;

        return $this->carService->store($carData);
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
            return response(['message' => 'Car not found'], ResponseAlias::HTTP_BAD_REQUEST);
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
            return response(['message' => 'Car not found'], ResponseAlias::HTTP_BAD_REQUEST);
        }

        return response(['message' => 'Successfully deleted'], ResponseAlias::HTTP_OK);
    }
}
