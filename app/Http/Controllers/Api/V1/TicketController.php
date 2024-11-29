<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Models\Ticket;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Requests\ReplaceTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filter)
    {
        $data = TicketResource::collection(Ticket::filter($filter)->paginate());
        return $data;
    }


    public function store(StoreTicketRequest $request)
    {
        try {
            User::findOrFail($request->input('data.relationships.author.data.id'));
        } catch (ModelNotFoundException $exception) {
            return $this->ok('User Not Found', [
                'error' => 'The provided user id does not exists',
            ]);
        }

        $model = [
            'title' => $request->input('data.attributes.title'),
            'description' => $request->input('data.attributes.description'),
            'status' => $request->input('data.attributes.status'),
            'user_id' => $request->input('data.relationships.author.data.id'),
        ];

        return new TicketResource(Ticket::create($model));
    }

    /**
     * Display the specified resource.
     */
    public function show($ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);

            if ($this->include('user')) {
                return new TicketResource($ticket->load('user'));
            }

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $ticketId)
    {
        // use PATCH method mean update one or to columns in database
    }

    public function replace(ReplaceTicketRequest $request, $ticketId) {
        // use PUT method mean replace all columns in database
        try {
            $ticket = Ticket::findOrFail($ticketId);

            $model = [
                'title' => $request->input('data.attributes.title'),
                'description' => $request->input('data.attributes.description'),
                'status' => $request->input('data.attributes.status'),
                'user_id' => $request->input('data.relationships.author.data.id'),
            ];
            $ticket->update($model);

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);
            $ticket->delete();

            return $this->ok('Ticket successfuly deleted');
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found', 404);
        }
    }
}
