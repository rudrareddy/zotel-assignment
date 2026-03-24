<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomType;
use App\Http\Requests\InventoryRequest;
use App\Services\InventoryService;

class InventoryController extends Controller
{
    protected InventoryService $service;

    // Dependency Injection (Service Layer)
    public function __construct(InventoryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display inventory list based on room type slug
     */
    public function index(Request $request, string $slug)
    {
        $data = $this->service->getInventoryByRoomType($slug, $request);

        return view('admin.inventory.index', $data);
    }

    /**
     * Show form to create inventory
     */
    public function create(string $slug)
    {
        $inventory = $this->service->getInventory($slug);

        return view('admin.inventory.create', compact('inventory'));
    }

    /**
     * Store new inventory records
     */
    public function store(InventoryRequest $request)
    {
        $this->service->createInventory($request);

        // Optimized: single query instead of first()
        $roomType = RoomType::findOrFail($request->room_type_id);

        return redirect("admin/inventory/{$roomType->slug}")
            ->with('success', 'Inventory created successfully.');
    }
}