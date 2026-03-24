<?php

namespace App\Repositories;

use App\Models\RoomInventory;
use App\Models\RoomType;
use Carbon\Carbon;

class InventoryRepository
{
    /**
     * Get paginated inventory with filters
     */
    public function getInventoryDates(string $slug, $request)
    {
        return RoomInventory::with('roomType:id,name,slug')
            ->whereHas('roomType', function ($q) use ($slug) {
                $q->where('slug', $slug);
            })
            // Filter: From Date
            ->when($request->date_from, function ($q, $date) {
                $q->whereDate('date', '>=', $date);
            })
            // Filter: To Date
            ->when($request->date_to, function ($q, $date) {
                $q->whereDate('date', '<=', $date);
            })
            ->latest('date')
            ->paginate(25)
            ->withQueryString();
    }

    /**
     * Get room type by slug
     */
    public function getRoomType(string $slug)
    {
        return RoomType::where('slug', $slug)->firstOrFail();
    }

    /**
     * Get latest inventory record
     */
    public function getInventory(string $slug)
    {
        return RoomInventory::select('id', 'room_type_id', 'date')
            ->with('roomType:id,name,slug,total_rooms')
            ->whereHas('roomType', function ($q) use ($slug) {
                $q->where('slug', $slug);
            })
            ->latest('date')
            ->first(); // latest is better than orderBy desc
    }

    /**
     * Create multiple inventory records (bulk insert optimized)
     */
    public function createInventory($request): void
    {
        // Start from next day of last inventory
        $startDate = Carbon::parse($request->last_inventory_date)->addDay();

        $data = [];

        for ($i = 0; $i < $request->inventory_date_count; $i++) {

            $date = $startDate->copy()->addDays($i);

            $data[] = [
                'room_type_id'       => $request->room_type_id,
                'date'               => $date->format('Y-m-d'),
                'available_rooms'   => $request->available_rooms,
                'base_price'        => $request->base_price,
                'extra_adult_price' => $request->extra_adult_price,
                'base_occupancy'    => $request->base_occupancy,
                'is_available'      => true,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }
        RoomInventory::insert($data);
    }
}