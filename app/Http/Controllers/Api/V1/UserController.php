<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\AuthorFilter;
use App\Http\Requests\Api\V1\ReplaceUserRequest;
use App\Models\User;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Policies\V1\UserPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends ApiController
{
    protected $policyClass = UserPolicy::class;
    /**
     * Display a listing of the resource.
     */
    public function index(AuthorFilter $filter)
    {
        return UserResource::collection(
            User::fileter($filter)->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $this->isAble('store', User::class); //  Policy

            return new UserResource(User::create($request->mappedAttributes()));
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to create this resource', 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if ($this->include('tickets')) {
            return new UserResource($user->load('tickets'));
        }

        return new UserResource($user);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);

            $this->isAble('update', $user); //  Policy

            $user->update($request->mappedAttributes());

            return new UserResource($user);
        } catch (ModelNotFoundException $exception) {
            return $this->error('User can not be found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update this resource', 401);
        }
    }

    public function replace(ReplaceUserRequest $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);

            $this->isAble('replace', $user); //  Policy

            $user->update($request->mappedAttributes());

            return new UserResource($user);
        } catch (ModelNotFoundException $exception) {
            return $this->error('User can not be found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update this resource', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($userId)
    {
        try {
            $user = User::findOrFail($userId);

            $this->isAble('delete', $user); //  Policy

            $user->delete();

            return $this->ok('User successfuly deleted');
        } catch (ModelNotFoundException $exception) {
            return $this->error('User can not be found', 404);
        }
    }
}
