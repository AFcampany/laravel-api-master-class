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
        if ($this->isAble('store', Ticket::class)) {
            return new TicketResource(Ticket::create($request->mappedAttributes()));
        }
        return $this->notAuthorized('You are not authorized to create this resource');
    }

    public function show(Ticket $ticket)
    {
        if ($this->include('user')) {
            return new TicketResource($ticket->load('user'));
        }

        return new TicketResource($ticket);
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        if ($this->isAble('update', $ticket)) {
            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);
        }

        return $this->notAuthorized('You are not authorized to update this resource');
    }

    public function replace(ReplaceTicketRequest $request, Ticket $ticket)
    {
        if ($this->isAble('replace', $ticket)) {
            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);
        }

        return $this->notAuthorized('You are not authorized to update this resource');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        if ($this->isAble('store', $ticket)) {
            $ticket->delete();

            return $this->ok('Ticket successfuly deleted');
        }

        return $this->notAuthorized('You are not authorized to delet this resource');
    }
}
