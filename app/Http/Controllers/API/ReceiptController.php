<?php 

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Receipt;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\ReceiptResource;

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
  public function store(Request $request)
  {
    $this->validate($request, [
      'name' => 'required|string',
      'description' => 'required|string',
      'task_id' => 'required|integer|exists:tasks,id',
      'total' => 'numeric|min:0',
      'is_paid' => 'boolean',
    ]);

    $input = $request->all();
    $input['created_at'] = Carbon::now();
    $input['created_by'] = auth()->user()->id;

    $receipt = Receipt::create($input);

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
        return $this->sendError('Receipt not found.');
    }

    return $this->sendResponse(new ReceiptResource($receipt), 'Receipt retrieved successfully.');    
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'name' => 'string',
      'description' => 'string',
      'task_id' => 'integer|exists:tasks,id',
      'total' => 'numeric|min:0',
      'is_paid' => 'boolean',
    ]);

    $receipt = Receipt::find($id);
    
    if (!$receipt) {
        return $this->sendError('Not found Error.', 'Sorry, Receipt with id ' . $id . ' cannot be found', 400);
    }

    $receipt->updated_at = Carbon::now();
    $receipt->updated_by = auth()->user()->id;

    $updated = $receipt->fill($request->all())->save();

    if (!$updated)
      return $this->sendError('Not update!.', 'Sorry, Receipt could not be updated', 500);

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
      return $this->sendError('Receipt not found.');
    }

    if($receipt->is_paid) {
      return $this->sendError('Can\'t delete!, Receipt is paid.');
    }

    $receipt->delete();

    return $this->sendResponse(new ReceiptResource($receipt), 'Receipt deleted successfully.');
  }
  
}

?>