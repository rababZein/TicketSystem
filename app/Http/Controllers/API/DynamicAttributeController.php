<?php 

namespace App\Http\Controllers\API;

use App\Http\Requests\DynamicAttributeRequest\AddDynamicAttributeRequest;
use App\Http\Requests\DynamicAttributeRequest\UpdateDynamicAttributeRequest;
use App\Http\Requests\DynamicAttributeRequest\ViewDynamicAttributeRequest;
use App\Http\Requests\DynamicAttributeRequest\DeleteDynamicAttributeRequest;
use App\Http\Requests\DynamicAttributeRequest\ListDynamicAttributeRequest;
use App\Models\DynamicAttribute;
use App\Models\User;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\API\BaseController;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\InvalidDataException;
use App\Exceptions\ItemNotFoundException;
use App\Exceptions\ItemNotDeletedException;
use App\Http\Resources\DynamicAttribute\DynamicAttributeCollection;
use App\Http\Resources\DynamicAttribute\DynamicAttributeResource;

class DynamicAttributeController extends BaseController 
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('permission:dynamic-attribute-list|dynamic-attribute-create|dynamic-attribute-edit|dynamic-attribute-delete', ['only' => ['index', 'list']]);
      $this->middleware('permission:dynamic-attribute-create', ['only' => ['store']]);
      $this->middleware('permission:dynamic-attribute-edit', ['only' => ['update']]);
      $this->middleware('permission:dynamic-attribute-delete', ['only' => ['destroy']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

  public function index(ListDynamicAttributeRequest $request)
  {
    $dynamicAttributes = DynamicAttribute::paginate();

    return $this->sendResponse(new DynamicAttributeCollection($dynamicAttributes), 'DynamicAttributes retrieved successfully.');
  }

  public function list(ListDynamicAttributeRequest $request)
  {
    $dynamicAttributes = DynamicAttribute::all();

    return $this->sendResponse(DynamicAttributeResource::collection($dynamicAttributes), 'DynamicAttributes retrieved successfully.');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(AddDynamicAttributeRequest $request)
  {
    $input = $request->validated();
    $input['created_by'] = auth()->user()->id;

    try {
      $dynamicAttribute = DynamicAttribute::create($input);
    } catch (Exception $ex) {
      throw new ItemNotCreatedException('DynamicAttribute');
    }

    return $this->sendResponse(new DynamicAttributeResource(DynamicAttribute::find($dynamicAttribute->id)), 'DynamicAttribute created successfully.');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show(DynamicAttribute $dynamicAttribute, ViewDynamicAttributeRequest $request)
  {
    return $this->sendResponse(new DynamicAttributeResource($dynamicAttribute), 'DynamicAttribute retrieved successfully.');    
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(DynamicAttribute $dynamicAttribute, UpdateDynamicAttributeRequest $request)
  {
    $input = $request->validated();

    $dynamicAttribute->updated_by = auth()->user()->id;

    try {
      $updated = $dynamicAttribute->fill($input)->save();
    } catch (\Exception $ex) {
      throw new ItemNotUpdatedException('DynamicAttribute');
    }    

    if (!$updated)
      throw new ItemNotUpdatedException('DynamicAttribute');

    return $this->sendResponse(new DynamicAttributeResource($dynamicAttribute), 'DynamicAttribute updated successfully.');    
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(DynamicAttribute $dynamicAttribute, DeleteDynamicAttributeRequest $request)
  {
    if($dynamicAttribute->users->isNotEmpty()) {
      throw new InvalidDataException([
        'tickets' => $dynamicAttribute->users->toArray()
      ],
      'Can\'t delete!, Some users has value for this DynamicAttribute.');
    }

    try {
      $dynamicAttribute->delete();
    } catch (\Exception $ex) {
      throw new ItemNotDeletedException('DynamicAttribute');
    }

    return $this->sendResponse(new DynamicAttributeResource($dynamicAttribute), 'DynamicAttribute deleted successfully.');
  }
}
