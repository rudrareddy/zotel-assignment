<?php

namespace App\Repositories;

use App\Models\RoomType;
use Illuminate\Support\Str;

class RoomTypeRepository
{
    /**
     * Get all room types
     */
    public function getAll()
    {
        return RoomType::latest()->get();
    }

    /**
     * Get single room type
     */
    public function getDetail(int $id)
    {
        return RoomType::findOrFail($id);
    }

    /**
     * Create new room type
     */
    public function create(array $data)
    {
        $data['slug'] = $this->generateUniqueSlug($data['name']);

        return RoomType::create($data);
    }

    /**
     * Update room type
     */
    public function update(RoomType $roomType, array $data)
    {
        $data['slug'] = $this->generateUniqueSlug($data['name'], $roomType->id);

        $roomType->update($data);

        return $roomType;
    }

    /**
     * Delete room type
     */
    public function delete(int $id)
    {
        return RoomType::destroy($id);
    }

    /**
     * Load room type with rate plans
     */
    public function getRoomTypeWithPlans(RoomType $roomType)
    {
        return $roomType->load('ratePlans');
    }

    /**
     * Generate unique slug
     */
    private function generateUniqueSlug(string $name, int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $count = RoomType::where('slug', 'LIKE', "{$slug}%")
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}