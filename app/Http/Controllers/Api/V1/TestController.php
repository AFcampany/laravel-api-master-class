<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\UserResource;

class TestController extends ApiController
{
    public function index() {
        return new UserResource(auth()->user());
    }
}
