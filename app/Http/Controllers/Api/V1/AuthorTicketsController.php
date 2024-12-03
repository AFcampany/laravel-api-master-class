<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorTicketsController extends ApiController
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
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request, $authorId)
    {
        try {
            $this->isAble('store', Ticket::class); //  Policy

            return new TicketResource(Ticket::create($request->mappedAttributes(['author' => 'user_id'])));
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to create this resource', 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function update(UpdateTicketRequest $request, $authorId, $ticketId)
    {
        // use PATCH method mean update one or to columns in database
        try {
            $ticket = Ticket::where('id', $ticketId)
                ->where('user_id', $authorId)
                ->firstOrFail();

            $this->isAble('update', $ticket);

            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to create this resource', 401);
        }
    }

    public function replace(ReplaceTicketRequest $request, $authorId, $ticketId)
    {
        // use PUT method mean replace all columns in database
        try {
            $ticket = Ticket::where('id', $ticketId)
                ->where('user_id', $authorId)
                ->firstOrFail();

            $this->isAble('replace', $ticket);

            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to create this resource', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($authorId, $ticketId)
    {
        try {
            $ticket = Ticket::where('id', $ticketId)
                ->where('user_id', $authorId)
                ->firstOrFail();

            $this->isAble('delete', $ticket);

            $ticket->delete();
            return $this->ok('Ticket successfuly deleted');
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to create this resource', 401);
        }
    }
}
