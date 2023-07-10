<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRentalRequest;
use App\Http\Requests\UpdateRentalRequest;
use App\Http\Resources\RentalResource;
use App\Http\Services\RentalService;
use App\Mail\CarRentedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RentalController extends Controller
{
    protected $rentalService;

    public function __construct(RentalService $rentalService)
    {
        $this->rentalService = $rentalService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $adminOrUserRentals = $this->rentalService->indexRentals($request);
        if($adminOrUserRentals->isEmpty())
            return response(['message'=>'Currently no rentals.'], ResponseAlias::HTTP_NOT_FOUND);
        return RentalResource::collection($adminOrUserRentals);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRentalRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        $rental = $this->rentalService->storeRental($validated);

        if(!$rental)
            return response(['message'=>'Car not available for rent at given dates.'], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);

        Mail::to(auth()->user()->email)->queue(new CarRentedMail($rental->car));
        return RentalResource::make($rental);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRentalRequest $request, $id)
    {
        $updatedRental = $this->rentalService->updateRental($request->validated(), $id);
        if(!$updatedRental)
            return response(['message' => 'Car not found'], ResponseAlias::HTTP_NOT_FOUND);
        return RentalResource::make($updatedRental);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deletedRental = $this->rentalService->delete($id);
        if(!$deletedRental)
            return response(['message' => 'Rental not found'], ResponseAlias::HTTP_NOT_FOUND);
        return response(['message' => 'Successfully deleted'], ResponseAlias::HTTP_OK);
    }

}
