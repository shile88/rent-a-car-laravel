<?php

namespace App\Http\Services;

use App\Exports\RentalExport;
use App\Models\Rental;
use Maatwebsite\Excel\Facades\Excel;

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

    public function exportExcel($request)
    {
        $type = $request->input('type');
        $model = $request->input('model');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $startPrice = $request->input('start_price');
        $endPrice = $request->input('end_price');

        $query = Rental::query()->with('car');
        if ($type) {
            $query->whereHas('car', function ($carQuery) use ($type) {
                $carQuery->where('type', 'like', '%' . $type . '%');
            });
        }

        if ($model) {
            $query->whereHas('car', function ($carQuery) use ($model) {
                $carQuery->where('model', 'like', '%' . $model . '%');
            });
        }

        if ($startDate && $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate])
                ->orWhereBetween('end_date', [$startDate, $endDate]);
        }

        if ($startPrice && $endPrice) {
            $query->whereHas('car', function ($carQuery) use ($startPrice, $endPrice) {
                $carQuery->whereBetween('price_per_day', [$startPrice, $endPrice])
                ;
            });
        }
        $rentalsAll = $query->get();

        if($rentalsAll->isEmpty())
            return false;

        return Excel::download(new RentalExport($rentalsAll), 'rentals.xlsx');
    }

}
