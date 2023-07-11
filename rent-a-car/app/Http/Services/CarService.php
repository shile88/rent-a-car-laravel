<?php

namespace App\Http\Services;

use App\Models\Car;

class CarService
{
    public function store($requestValidated)
    {
        return Car::query()->create($requestValidated);
    }

    public function update($requestValidated, $id)
    {
        $car = Car::find($id);
        if (!$car) {
            return false;
        }
        $car->update($requestValidated);
        return $car;
    }

    public function delete($id)
    {
        $car = Car::find($id);
        if (!$car)
            return false;
        return $car->delete();
    }

    public function searchCarByCriteria($request)
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

        if($cars->isEmpty()) {
            return false;
        }
        return $cars;
    }
}
