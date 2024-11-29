<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
        $data = $request->mappedAttributes();
        $data['user_id'] = $authorId;

        return new TicketResource(Ticket::create($data));
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
            $ticket = Ticket::findOrFail($ticketId);

            if ($ticket->user_id == $authorId) {
                $ticket->update($request->mappedAttributes());

                return new TicketResource($ticket);
            }
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found', 404);
        }
    }

    public function replace(ReplaceTicketRequest $request, $authorId, $ticketId) {
        // use PUT method mean replace all columns in database
        try {
            $ticket = Ticket::findOrFail($ticketId);

            if ($ticket->user_id == $authorId) {
                $ticket->update($request->mappedAttributes());

                return new TicketResource($ticket);
            }
            // TODO: ticket doesn't belong to user
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($authorId, $ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);

            if ($ticket->user_id == $authorId) {
                $ticket->delete();
                return $this->ok('Ticket successfuly deleted');
            }
            return $this->error('Ticket can not be found ', 404);

        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found', 404);
        }
    }
}
