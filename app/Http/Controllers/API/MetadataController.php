<?php 

namespace App\Http\Controllers\API;

use App\Http\Requests\MetadataRequest\AddMetadataRequest;
use App\Http\Requests\MetadataRequest\UpdateMetadataRequest;
use App\Http\Requests\MetadataRequest\ViewMetadataRequest;
use App\Http\Requests\MetadataRequest\DeleteMetadataRequest;
use App\Http\Requests\MetadataRequest\ListMetadataRequest;
use App\Models\Metadata;
use App\Http\Controllers\API\BaseController;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\ItemNotFoundException;
use App\Exceptions\ItemNotDeletedException;
use App\Http\Resources\Metadata\MetadataCollection;
use App\Http\Resources\Metadata\MetadataResource;

class MetadataController extends BaseController 
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index']]);
      $this->middleware('permission:user-create', ['only' => ['store']]);
      $this->middleware('permission:user-edit', ['only' => ['update']]);
      $this->middleware('permission:user-delete', ['only' => ['destroy']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

  public function index(ListMetadataRequest $request)
  {
    $metadatas = Metadata::paginate();
    
    return $this->sendResponse(new MetadataCollection($metadatas), 'Metadatas retrieved successfully.');
  }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(AddMetadataRequest $request)
  {
    $input = $request->validated();
    $input['created_by'] = auth()->user()->id;

    try {
      $metadata = Metadata::create($input);
    } catch (Exception $ex) {
      throw new ItemNotCreatedException('Metadata');
    }

    return $this->sendResponse(new MetadataResource($metadata), 'Metadata created successfully.');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show(Metadata $metadata, ViewMetadataRequest $request)
  {
    return $this->sendResponse(new MetadataResource($metadata), 'Metadata retrieved successfully.');    
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Metadata $metadata, UpdateMetadataRequest $request)
  {
    $input = $request->validated();

    $metadata->updated_by = auth()->user()->id;

    try {
      $updated = $metadata->fill($input)->save();
    } catch (\Exception $ex) {
      throw new ItemNotUpdatedException('Metadata');
    }    

    if (!$updated)
      throw new ItemNotUpdatedException('Metadata');

    return $this->sendResponse(new MetadataResource($metadata), 'Metadata updated successfully.');    
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(Metadata $metadata, DeleteMetadataRequest $request)
  {
    try {
      $metadata->delete();
    } catch (\Exception $ex) {
      throw new ItemNotDeletedException('Metadata');
    }

    return $this->sendResponse(new MetadataResource($metadata), 'Metadata deleted successfully.');
  }
}
