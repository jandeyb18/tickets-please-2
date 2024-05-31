<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\V1\TicketPolicy;
use Database\Factories\TicketFactory;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class TicketController extends ApiController
{

    protected $policyClass = TicketPolicy::class;

    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filters)
    {

        return TicketResource::collection(Ticket::filter($filters)->paginate());

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        try{
            $user = User::findOrFail($request->input('data.relationships.author.data.id'));
        }catch(ModelNotFoundException $exception) {
            return $this->ok('User not found', [
                'error' => 'The provided user id does not exist'
            ]);try{
            $user = User::findOrFail($request->input('data.relationships.author.data.id'));
        }catch(ModelNotFoundException $exception) {
            return $this->ok('User not found', [
                'error' => 'The provided user id does not exist'
            ]);
        }

        return new TicketResource($request->mappedAttributes()) ;
        }

        $model = [
            'title' => $request->input('data.attributes.title'),
            'description' => $request->input('data.attributes.description'),
            'status' => $request->input('data.attributes.status'),
            'user_id' => $request->input('data.relationships.author.data.id')
        ];

        return new TicketResource(Ticket::create($model) ) ;
    }

    /**
     * Display the specified resource.
     */
    public function show($ticket_id)
    {
 
        try {
            $ticket = Ticket::FindOrFail($ticket_id);

            if ($this->include('author')) {
                return  new TicketResource($ticket->load('user')) ;    
            }
                
            return new TicketResource($ticket);
        }catch(ModelNotFoundException $exception) {
            return $this->error('Ticket not found', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $ticket_id)
    {
        // PATCH
        try{
            $ticket = Ticket::findOrFail($ticket_id);

            //policy
            Gate::authorize('update', $ticket);
 
// The action is authorized...

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        }catch(ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found', 404); 
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update resource', 401);
        }
    } 

    public function replace(ReplaceTicketRequest $request, $ticket_id)
    {
        //PUT
        try{
            $ticket = Ticket::findOrFail($ticket_id);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        }catch(ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found', 404); 
        }
    } 

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            $ticket->delete();

            return $this->ok('Ticket successfully deleted');
        }catch(ModelNotFoundException $exception) {
            return $this->error('Ticket not found', 404);
        }
    }
}
