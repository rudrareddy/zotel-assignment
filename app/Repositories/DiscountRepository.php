<?php
namespace App\Repositories;

use App\Models\Discount;

class DiscountRepository {

    public function getAll()
    {
        return Discount::latest()->get();
    }

    public function create(array $data){
        return Discount::create($data);
    }
}