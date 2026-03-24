<?php

namespace App\Services;

use App\Repositories\InventoryRepository;
use Illuminate\Http\Request;

class InventoryService
{
    protected InventoryRepository $repo;

    public function __construct(InventoryRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Get inventory list with filters
     */
    public function getInventoryByRoomType(string $slug, Request $request): array
    {
        return [
            'inventory_dates' => $this->repo->getInventoryDates($slug, $request),
            'room_type' => $this->repo->getRoomType($slug),
        ];
    }

    /**
     * Get latest inventory record for room type
     */
    public function getInventory(string $slug)
    {
        return $this->repo->getInventory($slug);
    }

    /**
     * Create multiple inventory records
     */
    public function createInventory($request): void
    {
        $this->repo->createInventory($request);
    }
}