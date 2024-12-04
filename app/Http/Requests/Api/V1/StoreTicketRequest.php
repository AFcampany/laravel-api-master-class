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

        $user = $this->user();
        $rules =  [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            $authorIdAttr => "required|integer|exists:users,id|size:$user->id",
        ];

        if ($this->routeIs('tickets.store')) {
            if ($user->tokenCan(Abilities::CreateOwnTicket)) {
                $rules[$authorIdAttr] = "required|integer|exists:users,id";
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
