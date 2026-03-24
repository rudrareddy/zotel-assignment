<?php

namespace App\Services;

use App\Models\RatePlan;
use App\Repositories\RatePlanRepository;

class RatePlanService
{
    protected RatePlanRepository $repo;

    public function __construct(RatePlanRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Get all rate plans
     */
    public function list()
    {
        return $this->repo->getAll();
    }

    /**
     * Create rate plan
     */
    public function store(array $data)
    {
        return $this->repo->create($data);
    }

    /**
     * Update rate plan
     */
    public function update(RatePlan $ratePlan, array $data)
    {
        return $this->repo->update($ratePlan, $data);
    }

    /**
     * Delete rate plan
     */
    public function delete(int $id)
    {
        return $this->repo->delete($id);
    }
}