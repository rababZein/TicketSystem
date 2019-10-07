<?php 

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Tracking_task;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\API\BaseController;

class Tracking_taskController extends BaseController 
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('permission:tracking_task-create', ['only' => ['store']]);
      $this->middleware('permission:tracking_task-edit', ['only' => ['update']]);
      $this->middleware('permission:tracking_task-delete', ['only' => ['destroy']]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'comment' => 'required|string',
      'start_at' => 'required|date_format:Y-m-d H:i:s',
      'end_at' => 'date_format:Y-m-d H:i:s',
      'tack_id' => 'integer|exists:tasks,id',
      'count_time' => 'numeric|min:0'
    ]);

    if($validator->fails()){
       return $this->sendError('Validation Error.', $validator->errors());      
    }

    $input = $request->all();
    $input['created_at'] = Carbon::now();
    $input['created_by'] = auth()->user()->id;

    if (! isset($input['start_at'])) {
      $input['start_at'] = Carbon::now();
    }

    $tracking_task = Tracking_task::create($input);

    return $this->sendResponse($tracking_task->toArray(), 'Tracking task created successfully.');
    
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'comment' => 'string',
      'start_at' => 'date_format:Y-m-d H:i:s',
      'end_at' => 'date_format:Y-m-d H:i:s',
      'tack_id' => 'integer|exists:tasks,id',
      'count_time' => 'numeric|min:0'
    ]);

    if($validator->fails()){
        return $this->sendError('Validation Error.', $validator->errors());       
    }

    $traking_task = Traking_task::find($id);
    
    if (!$traking_task) {
        return $this->sendError('Not found Error.', 'Sorry, traking task with id ' . $id . ' cannot be found', 400);
    }

    $traking_task->updated_at = Carbon::now();
    $traking_task->updated_by = auth()->user()->id;

    $updated = $tracking_task->fill($request->all())->save();

    if (!$updated)
      return $this->sendError('Not update!.', 'Sorry, Traking task could not be updated', 500);

    return $this->sendResponse($traking_task->toArray(), 'Traking task updated successfully.');    
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    $traking_task = Traking_task::find($id);

    if (is_null($traking_task)) {
      return $this->sendError('Traking task not found.');
    }

    if($traking_task->task->receipts->isNotEmpty()) {
      return $this->sendError('Can\'t delete!, This task has receipts.');
    }

    $traking_task->delete();

    return $this->sendResponse($traking_task->toArray(), 'Traking task deleted successfully.');
  }


  
  
}

?>