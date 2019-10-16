<?php 

namespace App\Http\Controllers\API;

use App\Http\Requests\ReceiptRequest\AddReceiptRequest;
use App\Http\Requests\ReceiptRequest\UpdateReceiptRequest;
use App\Models\Receipt;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\API\BaseController;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\InvalidDataException;
use App\Exceptions\ItemNotFoundException;
use App\Exceptions\ItemNotDeletedException;
use App\Http\Resources\ReceiptResource;
use App\Notifications\Receipt\ReceiptPaid;

class ReceiptController extends BaseController 
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('permission:receipt-list|receipt-create|receipt-edit|receipt-delete', ['only' => ['index']]);
      $this->middleware('permission:receipt-create', ['only' => ['store']]);
      $this->middleware('permission:receipt-edit', ['only' => ['update']]);
      $this->middleware('permission:receipt-delete', ['only' => ['destroy']]);
  }

  /**
   * Display a view listing of the resource view.
   *
   * @return Response
   */
  public function index()
  {
    return view('pages.receipts.index');
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function getAll()
  {
    $receipts = Receipt::with('task')->get();

    return $this->sendResponse(ReceiptResource::collection($receipts), 'Receipts retrieved successfully.');
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

    if ($input['is_paid']) {
      auth()->user()->notify(new ReceiptPaid($receipt));
    }

    return $this->sendResponse(new ReceiptResource($receipt), 'Receipt created successfully.');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
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

    $input = $request->all();

    $updated = $receipt->fill($input)->save();
    
    try {
      $updated = $receipt->fill($request->validated())->save();
    } catch (\Throwable $th) {
      throw new ItemNotUpdatedException('Receipt');
    }

    if (!$updated)
      throw new ItemNotUpdatedException('Receipt');

    if (isset($input['is_paid']) && $input['is_paid']) {
      auth()->user()->notify(new ReceiptPaid($receipt));
    }

    return $this->sendResponse(new ReceiptResource($receipt), 'Receipt updated successfully.');  
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
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