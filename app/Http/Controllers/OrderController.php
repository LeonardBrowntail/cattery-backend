<?php

namespace App\Http\Controllers;

use App\Http\ApiTraits\OrderAPIResponses;
use App\Http\Requests\IndexOrderRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use OrderAPIResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(IndexOrderRequest $request )
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
