<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\ReplaceTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
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
        $model = [
            'title' => $request->input('data.attributes.title'),
            'description' => $request->input('data.attributes.description'),
            'status' => $request->input('data.attributes.status'),
            'user_id' => $authorId,
        ];

        return new TicketResource(Ticket::create($model));
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

    public function replace(ReplaceTicketRequest $request, $authorId, $ticketId) {
        // use PUT method mean replace all columns in database
        try {
            $ticket = Ticket::findOrFail($ticketId);

            if ($ticket->user_id == $authorId) {
                $model = [
                    'title' => $request->input('data.attributes.title'),
                    'description' => $request->input('data.attributes.description'),
                    'status' => $request->input('data.attributes.status'),
                    'user_id' => $request->input('data.relationships.author.data.id'),
                ];
                $ticket->update($model);

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
