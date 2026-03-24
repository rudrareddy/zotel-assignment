<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DiscountService;
use App\Http\Requests\DiscountRequest;
class DiscountController extends Controller
{
    public function __construct(protected DiscountService $service){
        $this->service= $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discounts =  $this->service->list();
        return view('admin.discounts.index',compact('discounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->service->getRatePlansAndRoomType();
        return view('admin.discounts.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DiscountRequest $request)
    {
        $create = $this->service->create($request->validated());
        return redirect('admin/discounts')->with('success', 'Discount created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
