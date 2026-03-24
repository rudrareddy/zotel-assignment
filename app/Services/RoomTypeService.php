<?php

namespace App\Services;

use App\Repositories\RoomTypeRepository;
use App\Models\RoomType;

class RoomTypeService
{
    protected RoomTypeRepository $repo;
    protected RatePlanService $rateService;

    public function __construct(
        RoomTypeRepository $repo,
        RatePlanService $rateService
    ) {
        $this->repo = $repo;
        $this->rateService = $rateService;
    }

    /**
     * Get all room types
     */
    public function list()
    {
        return $this->repo->getAll();
    }

    /**
     * Create room type
     */
    public function store(array $data)
    {
        return $this->repo->create($data);
    }

    /**
     * Update room type
     */
    public function update(RoomType $roomType, array $data)
    {
        return $this->repo->update($roomType, $data);
    }

    /**
     * Delete room type
     */
    public function delete(int $id)
    {
        return $this->repo->delete($id);
    }

    /**
     * Get room type with rate plans
     */
    public function getRatePlansAndRoomType(RoomType $roomType): array
    {
        return [
            'ratePlans' => $this->rateService->list(),
            'roomType'  => $this->repo->getRoomTypeWithPlans($roomType),
        ];
    }

    /**
     * Sync rate plans (optimized)
     */
    public function syncRatePlans(RoomType $roomType, array $ratePlans): void
    {
        $syncData = [];

        foreach ($ratePlans as $planId => $plan) {

            // Only sync selected plans
            if (!isset($plan['selected'])) {
                continue;
            }

            $syncData[$planId] = [
                'base_price_multiplier' => $plan['base_price_multiplier'] ?? 1,
                'meal_price_per_person' => $plan['meal_price_per_person'] ?? 0,
                'is_available'          => $plan['is_available'] ?? 1,
            ];
        }

        // Sync pivot table
        $roomType->ratePlans()->sync($syncData);
    }
}