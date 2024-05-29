<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\AuthorFilter;
use App\Models\user;
use App\Http\Requests\Api\V1\StoreuserRequest;
use App\Http\Requests\Api\V1\UpdateuserRequest;
use App\Http\Resources\UserResource;

class AuthorsController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(user::filter($filters)->paginate());
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreuserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(user $author)
    {
        if ($this->include('tickets')) {
            return new UserResource($author->load('tickets'));
        }

        return new UserResource($author);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateuserRequest $request, user $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(user $user)
    {
        //
    }
}
