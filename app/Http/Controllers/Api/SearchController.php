<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Services\HotelSearchService;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    protected $searchService;

    public function __construct(HotelSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function search(SearchRequest $request): JsonResponse
    {
        $results = $this->searchService->search($request);
        
        if (isset($results['error'])) {
            return response()->json([
                'success' => false,
                'error' => $results['error']
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }
}