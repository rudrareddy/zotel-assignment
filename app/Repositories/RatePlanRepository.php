<?php

namespace App\Repositories;

use App\Models\RatePlan;

class RatePlanRepository
{
    /**
     * Get all rate plans
     */
    public function getAll()
    {
        return RatePlan::latest()->get();
    }

    /**
     * Get single rate plan
     */
    public function getDetail(int $id)
    {
        return RatePlan::findOrFail($id);
    }

    /**
     * Create rate plan
     */
    public function create(array $data)
    {
        return RatePlan::create($data);
    }

    /**
     * Update rate plan
     */
    public function update(RatePlan $ratePlan, array $data)
    {
        $ratePlan->update($data);

        return $ratePlan;
    }

    /**
     * Delete rate plan
     */
    public function delete(int $id)
    {
        return RatePlan::destroy($id);
    }
}