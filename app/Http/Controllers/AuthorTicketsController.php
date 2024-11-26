<?php

namespace App\Http\Controllers;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AuthorTicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filter, $authorId)
    {
        return TicketResource::collection(
            Ticket::where('user_id', $authorId)->filter($filter)->paginate()
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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