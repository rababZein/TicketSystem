<?php 

namespace App\Http\Controllers\API;

use App\Http\Requests\ReceiptRequest\AddReceiptRequest;
use App\Http\Requests\ReceiptRequest\UpdateReceiptRequest;
use App\Models\Receipt;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\API\BaseController;

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

    return $this->sendResponse($receipts->toArray(), 'Receipts retrieved successfully.');
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

    $receipt = Receipt::create($input);

    return $this->sendResponse($receipt->toArray(), 'Receipt created successfully.');
    
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

    return $this->sendResponse($receipt->toArray(), 'Receipt retrieved successfully.');    
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
        return $this->sendError('Not found Error.', 'Sorry, Receipt with id ' . $id . ' cannot be found', 400);
    }

    $receipt->updated_at = Carbon::now();
    $receipt->updated_by = auth()->user()->id;

    $updated = $receipt->fill($request->validated())->save();

    if (!$updated)
      return $this->sendError('Not update!.', 'Sorry, Receipt could not be updated', 500);

    return $this->sendResponse($receipt->toArray(), 'Receipt updated successfully.');    
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

    return $this->sendResponse($receipt->toArray(), 'Receipt deleted successfully.');
  }
  
}

?>