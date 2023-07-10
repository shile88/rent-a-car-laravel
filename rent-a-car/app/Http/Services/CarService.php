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
}
