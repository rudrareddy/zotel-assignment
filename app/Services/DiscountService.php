<?php
namespace App\Services;

use App\Repositories\DiscountRepository;
use App\Services\RatePlanService;
use App\Services\RoomTypeService;
class DiscountService {
     
    public function __construct(protected DiscountRepository $repo,protected RatePlanService $rate_service,protected RoomTypeService $room_service){
        $this->repo = $repo;
        $this->rate_service = $rate_service;
        $this->room_service = $room_service;
    }


    public function list(){
        return $this->repo->getAll();
    }

    public function getRatePlansAndRoomType(){
        $rate_plans = $this->rate_service->list();
        $room_types = $this->room_service->list();
        return compact('rate_plans','room_types');
    }

    public function create(array $data){
        return $this->repo->create($data);
    }

}