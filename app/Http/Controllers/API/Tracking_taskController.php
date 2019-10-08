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
      'task_id' => 'integer|exists:tasks,id'
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

    if (isset($input['end_at'])){
      $input['count_time'] = Carbon::parse($input['end_at'])->diffInSeconds(Carbon::parse($input['start_at']));
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
      'task_id' => 'integer|exists:tasks,id',
      'count_time' => 'numeric|min:0'
    ]);

    if($validator->fails()){
        return $this->sendError('Validation Error.', $validator->errors());       
    }

    $tracking_task = Tracking_task::find($id);

    if (!$tracking_task) {
        return $this->sendError('Not found Error.', 'Sorry, traking task with id ' . $id . ' cannot be found', 400);
    }

    $tracking_task->updated_at = Carbon::now();
    $tracking_task->updated_by = auth()->user()->id;

    $input = $request->all();
    if (isset($input['end_at'])){
      if (isset($input['start_at']))
        $input['count_time'] = Carbon::parse($input['end_at'])->diffInSeconds(Carbon::parse($input['start_at']));
      else
        $input['count_time'] = Carbon::parse($input['end_at'])->diffInSeconds($tracking_task->start_at);
    }

    $updated = $tracking_task->fill($input)->save();

    if (!$updated)
      return $this->sendError('Not update!.', 'Sorry, Tracking task could not be updated', 500);

    return $this->sendResponse($tracking_task->toArray(), 'Tracking task updated successfully.');    
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    $tracking_task = Tracking_task::find($id);

    if (is_null($tracking_task)) {
      return $this->sendError('Traking task not found.');
    }

    if($tracking_task->task->receipts->isNotEmpty()) {
      return $this->sendError('Can\'t delete!, This task has receipts.');
    }

    $tracking_task->delete();

    return $this->sendResponse($tracking_task->toArray(), 'Tracking task deleted successfully.');
  }

 /**
 * Count Duration for a specfic task.
 *
 * @param  int  $task_id
 * @return Response
 */

  public function tracking($task_id)
  {
    $tracking_model = new Tracking_task();
    $tracking = $tracking_model->tarking($task_id);

    return $this->sendResponse(['tracking' => $tracking], 'Traking task counter retrived successfully.');
  }

/**
 * Check there is a tracking in-progress
 *
 * @param  int  $task_id
 * @return Response
 */
 public function checkTrackingInProgress($task_id)
{
  $tracking_model = new Tracking_task();
  $tracking = $tracking_model->inProgressTracking($task_id);

  return $this->sendResponse($tracking->toArray(), 'Traking task counter retrived successfully.');
}
  
  
}

?>