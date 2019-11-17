<?php

namespace Modules\ClientComment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\ClientComment\Entities\ClientComment;

use Modules\ClientComment\Http\Requests\AddClientCommentRequest;
use Modules\ClientComment\Http\Requests\UpdateClientCommentRequest;
use Modules\ClientComment\Http\Requests\DeleteClientCommentRequest;

use Modules\ClientComment\Http\Resources\ClientCommentResource;
use Modules\ClientComment\Http\Resources\ClientCommentCollection;

use App\Http\Controllers\API\BaseController;

use App\Exceptions\InvalidDataException;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\ItemNotDeletedException;

use Carbon\Carbon;

class ClientCommentController extends BaseController
{
        
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:clientComment-list|clientComment-create|clientComment-edit|clientComment-delete', ['only' => ['index', 'show', 'getCommentsPerClient']]);
        $this->middleware('permission:clientComment-create', ['only' => ['store']]);
        $this->middleware('permission:clientComment-edit', ['only' => ['update']]);
        $this->middleware('permission:clientComment-delete', ['only' => ['destroy']]);
    }
     /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $clientComments = ClientComment::with('client', 'creator', 'updater')->paginate();

        return $this->sendResponse(new ClientCommentCollection($clientComments), 'ClientCommenties retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     * @param AddClientCommentRequest $request
     * @return Response
     */
    public function store(AddClientCommentRequest $request)
    {
        $input = $request->validated();
        $input['created_at'] = Carbon::now();
        $input['created_by'] = auth()->user()->id;
    
        try {
          $clientComment = ClientComment::create($input);
        } catch (Exception $ex) {
          throw new ItemNotCreatedException('ClientComment');
        }

        return $this->sendResponse(new ClientCommentResource($clientComment), 'ClientComment created successfully.');
    }

    /**
     * Show the specified resource.
     * @param ClientComment $clientComment
     * @return Response
     */
    public function show(ClientComment $clientComment)
    {
        return $this->sendResponse(new ClientCommentResource($clientComment), 'ClientComment retrieved successfully.');    
    }

    /**
     * Update the specified resource in storage.
     * @param ClientComment $clientComment
     * @param UpdateClientCommentRequest $request
     * @return Response
     */
    public function update(ClientComment $clientComment, UpdateClientCommentRequest $request)
    {
        $input = $request->validated();

        $clientComment->updated_at = Carbon::now();
        $clientComment->updated_by = auth()->user()->id;

        try {
            $updated = $clientComment->fill($input)->save();
        } catch (\Exception $ex) {
            throw new ItemNotUpdatedException('ClientComment');
        }    

        if (!$updated)
        throw new ItemNotUpdatedException('ClientComment');

        return $this->sendResponse(new ClientCommentResource($clientComment), 'ClientComment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param ClientComment $clientComment
     * @param DeleteClientCommentRequest $request
     * @return Response
     */
    public function destroy(ClientComment $clientComment, DeleteClientCommentRequest $request)
    {
        try {
            $clientComment->delete();
        } catch (\Exception $ex) {
            throw new ItemNotDeletedException('ClientComment');
        }
      
        return $this->sendResponse(new ClientCommentResource($clientComment), 'ClientComment deleted successfully.');
    }

    /**
     * Display a listing of the resource by ClientId
     * @param $clientId
     * @return Response
     */
    public function getCommentsPerClient($clientId)
    {
        $clientComments = ClientComment::with('creator', 'updater')->where('client_id', $clientId)
                                       ->paginate();

        return $this->sendResponse(new ClientCommentCollection($clientComments), 'ClientCommenties retrieved successfully.');
    }
}
