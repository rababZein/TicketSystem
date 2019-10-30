<?php 

namespace App\Http\Controllers\API;

use App\Http\Requests\ReceiptRequest\AddReceiptRequest;
use App\Http\Requests\ReceiptRequest\UpdateReceiptRequest;
use App\Http\Requests\ReceiptRequest\ViewReceiptRequest;
use App\Http\Requests\ReceiptRequest\DeleteReceiptRequest;
use App\Http\Requests\ReceiptRequest\ListReceiptRequest;
use App\Models\Receipt;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\API\BaseController;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\InvalidDataException;
use App\Exceptions\ItemNotFoundException;
use App\Exceptions\ItemNotDeletedException;
use App\Http\Resources\Receipt\ReceiptCollection;
use App\Http\Resources\Receipt\ReceiptResource;

class ReceiptController extends BaseController 
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('permission:receipt-list|receipt-create|receipt-edit|receipt-delete', ['only' => ['index', 'getAll']]);
      $this->middleware('permission:receipt-create', ['only' => ['store']]);
      $this->middleware('permission:receipt-edit', ['only' => ['update']]);
      $this->middleware('permission:receipt-delete', ['only' => ['destroy']]);
  }

  /**
   * Display a view listing of the resource view.
   *
   * @return Response
   */
  public function index(ListReceiptRequest $request)
  {
    return view('pages.receipts.index');
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function getAll(ListReceiptRequest $request)
  {
    $receipts = Receipt::with('task')->get();

    return $this->sendResponse(ReceiptResource::collection($receipts), 'Receipts retrieved successfully.');
  }

  public function list()
  {
    $receipts = Receipt::with('task')->paginate();

    return $this->sendResponse(new ReceiptCollection($receipts), 'Receipts retrieved successfully.');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(AddReceiptRequest $request)
  {
    $input = $request->validated();
    $input['created_at'] = Carbon::now();
    $input['created_by'] = auth()->user()->id;

    try {
      $receipt = Receipt::create($input);
    } catch (\Throwable $th) {
      throw new ItemNotCreatedException('Receipt');
    }

    return $this->sendResponse(new ReceiptResource($receipt), 'Receipt created successfully.');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show(ViewReceiptRequest $request, $id)
  {
    $receipt = Receipt::find($id);

    if (is_null($receipt)) {
      throw new ItemNotFoundException($id);
    }

    return $this->sendResponse(new ReceiptResource($receipt), 'Receipt retrieved successfully.');    
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(UpdateReceiptRequest $request, $id)
  {
    $receipt = Receipt::find($id);
    
    if (!$receipt) {
      throw new ItemNotFoundException($id);
    }

    $receipt->updated_at = Carbon::now();
    $receipt->updated_by = auth()->user()->id;

    $input = $request->validated();
    
    try {
      $updated = $receipt->fill($input)->save();
    } catch (\Throwable $th) {
      throw new ItemNotUpdatedException('Receipt');
    }

    if (!$updated)
      throw new ItemNotUpdatedException('Receipt');

    return $this->sendResponse(new ReceiptResource($receipt), 'Receipt updated successfully.');  
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(DeleteReceiptRequest $request, $id)
  {
    $receipt = Receipt::find($id);

    if (is_null($receipt)) {
      throw new ItemNotFoundException($id);
    }

    if($receipt->is_paid) {
      throw new InvalidDataException([
        'receipt' => $receipt->toArray()
      ],
      'Can\'t delete!, Receipt is paid.');
    }

    try {
      $receipt->delete();
    } catch (\Throwable $th) {
      throw new ItemNotDeletedException('Receipt');
    }

    return $this->sendResponse(new ReceiptResource($receipt), 'Receipt deleted successfully.');
  }
}

?>