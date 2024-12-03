<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Models\Ticket;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Policies\V1\TicketPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{
    protected $policyClass = TicketPolicy::class;

    public function index(TicketFilter $filter)
    {
        return TicketResource::collection(Ticket::filter($filter)->paginate());
    }


    public function store(StoreTicketRequest $request)
    {
        try {
            $this->isAble('store', Ticket::class); //  Policy

            return new TicketResource(Ticket::create($request->mappedAttributes()));
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to create this resource', 401);
        }
    }

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

    public function update(UpdateTicketRequest $request, $ticketId)
    {
        // use PATCH method mean update one or to columns in database
        try {
            $ticket = Ticket::findOrFail($ticketId);

            $this->isAble('update', $ticket); //  Policy

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update this resource', 401);
        }
    }

    public function replace(ReplaceTicketRequest $request, $ticketId)
    {
        // use PUT method mean replace all columns in database
        try {
            $ticket = Ticket::findOrFail($ticketId);

            $this->isAble('replace', $ticket); //  Policy

            $ticket->update($request->mappedAttributes());

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

            $this->isAble('delete', $ticket); //  Policy

            $ticket->delete();

            return $this->ok('Ticket successfuly deleted');
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found', 404);
        }
    }
}
