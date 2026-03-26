<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RatePlanService;
use App\Models\RatePlan;

class RatePlanController extends Controller
{
    protected RatePlanService $service;

    public function __construct(RatePlanService $service)
    {
        $this->service = $service;
    }

    /**
     * List all rate plans
     */
    public function index()
    {
        $plans = $this->service->list();

        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.plans.create');
    }

    /**
     * Store new rate plan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'meal_type'   => 'required|string|unique:rate_plans,meal_type',
            'description' => 'required|string',
            'is_active'   => 'required|boolean',
        ]);

        $this->service->store($validated);

        return redirect()->route('rate-plans.index')
            ->with('success', 'Rate Plan created successfully.');
    }

    /**
     * Edit form
     */
    public function edit(RatePlan $ratePlan)
    {
        // Eager load relationships
        $ratePlan->load('roomTypes');
        //return $ratePlan;

        return view('admin.plans.edit', compact('ratePlan'));
    }

    /**
     * Update rate plan
     */
    public function update(Request $request, RatePlan $ratePlan)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $this->service->update($ratePlan, $validated);

        return redirect()->route('rate-plans.index')
            ->with('success', 'Rate Plan updated successfully.');
    }

    /**
     * Delete rate plan
     */
    public function destroy(RatePlan $ratePlan)
    {
        $this->service->delete($ratePlan->id);

        return redirect()->route('rate-plans.index')
            ->with('success', 'Rate Plan deleted successfully.');
    }
}