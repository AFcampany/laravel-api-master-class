<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

class StoreTicketRequest extends BaseTicketRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $authorIdAttr = $this->routeIs('tickets.store') ? 'data.relationships.author.data.id' : 'author';
        $rules =  [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            $authorIdAttr => 'required|integer|exists:users,id',
        ];

        $user = $this->user();
        if ($this->routeIs('tickets.store')) {
            if ($user->tokenCan(Abilities::CreateOwnTicket)) {
                $rules[$authorIdAttr] .= "|size:$user->id";
            }
        }

        return $rules;
    }

    protected function prepareForValidation() {
        if ($this->routeIs('authors.tickets.store')) {
            $this->merge([
                'author' => $this->route('author'),
            ]);
        }
    }
}
