<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Services\RoomTypeService;
use App\Http\Requests\RoomTypeRequest;
use App\Http\Requests\RoomTypeUpdateRequest;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    protected RoomTypeService $service;

    // Dependency Injection
    public function __construct(RoomTypeService $service)
    {
        $this->service = $service;
    }

    /**
     * List all room types
     */
    public function index()
    {
        $rooms = $this->service->list();

        return view('admin.room_types.index', compact('rooms'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.room_types.create');
    }

    /**
     * Show rate plans for a room type
     */
    public function show(RoomType $roomType)
    {
        $data = $this->service->getRatePlansAndRoomType($roomType);

        return view('admin.room_types.rates', $data);
    }

    /**
     * Store new room type
     */
    public function store(RoomTypeRequest $request)
    {
        $this->service->store($request->validated());

        return redirect()->route('room-types.index')
            ->with('success', 'Room Type created successfully.');
    }

    /**
     * Edit form
     */
    public function edit(RoomType $roomType)
    {
        return view('admin.room_types.edit', compact('roomType'));
    }

    /**
     * Update room type
     */
    public function update(RoomTypeUpdateRequest $request, RoomType $roomType)
    {
        $this->service->update($roomType, $request->validated());

        return redirect()->route('room-types.index')
            ->with('success', 'Room Type updated successfully.');
    }

    /**
     * Delete room type
     */
    public function destroy(RoomType $roomType)
    {
        $this->service->delete($roomType->id);

        return redirect()->route('room-types.index')
            ->with('success', 'Room Type deleted successfully.');
    }

    /**
     * Update rate plans (Many-to-Many sync)
     */
    public function updateRates(Request $request, RoomType $roomType)
    {
        $this->service->syncRatePlans($roomType, $request->rate_plans ?? []);

        return redirect()->route('room-types.index')
            ->with('success', 'Rate plans updated successfully.');
    }
}