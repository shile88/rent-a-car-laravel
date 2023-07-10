<?php

namespace App\Http\Services;

use App\Models\Rental;

class RentalService
{
    public function indexRentals($request)
    {
        $admin = auth()->user()->isAdmin;
        $user = auth()->user()->id;
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');


        if(!$admin)
            return Rental::query()->where('user_id', $user)
                ->whereBetween('start_date', [$startDate, $endDate])
                ->orWhereBetween('end_date', [$startDate, $endDate])
                ->get();

        return Rental::query()->whereBetween('start_date', [$startDate, $endDate])
            ->orWhereBetween('end_date', [$startDate, $endDate])
            ->get();
    }
    public function storeRental($requestValidated)
    {
        $availableCar = Rental::query()
            ->where('car_id', $requestValidated['car_id'])
            ->whereBetween('start_date',[$requestValidated['start_date'], $requestValidated['end_date']])
            ->orWhereBetween('end_date',[$requestValidated['start_date'], $requestValidated['end_date']])
            ->get();

        if(!$availableCar) {
            return false;
        }

        return Rental::query()->create($requestValidated);
    }

    public function updateRental($requestValidated, $id)
    {
        $rental = Rental::find($id);
        if (!$rental) {
            return false;
        }
        $rental->update($requestValidated);
        return $rental;
    }

    public function delete($id)
    {
        $rental = Rental::find($id);
        if (!$rental)
            return false;
        return $rental->delete();
    }

}
