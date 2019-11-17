<?php

namespace Modules\TaskComment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\TaskComment\Entities\TaskComment;

use Modules\TaskComment\Http\Requests\AddTaskCommentRequest;
use Modules\TaskComment\Http\Requests\UpdateTaskCommentRequest;
use Modules\TaskComment\Http\Requests\DeleteTaskCommentRequest;

use Modules\TaskComment\Http\Resources\TaskCommentResource;
use Modules\TaskComment\Http\Resources\TaskCommentCollection;

use App\Http\Controllers\API\BaseController;

use App\Exceptions\InvalidDataException;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\ItemNotDeletedException;

use Carbon\Carbon;

class TaskCommentController extends BaseController
{
        
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:taskComment-list|taskComment-create|taskComment-edit|taskComment-delete', ['only' => ['index', 'show', 'getCommentsPerTask']]);
        $this->middleware('permission:taskComment-create', ['only' => ['store']]);
        $this->middleware('permission:taskComment-edit', ['only' => ['update']]);
        $this->middleware('permission:taskComment-delete', ['only' => ['destroy']]);
    }
     /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $taskComments = TaskComment::with('client', 'creator', 'updater')->paginate();

        return $this->sendResponse(new TaskCommentCollection($taskComments), 'taskCommenties retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     * @param AddTaskCommentRequest $request
     * @return Response
     */
    public function store(AddTaskCommentRequest $request)
    {
        $input = $request->validated();

        $input['created_at'] = Carbon::now();
        $input['created_by'] = auth()->user()->id;
    
        try {
          $taskComment = TaskComment::create($input);
        } catch (Exception $ex) {
          throw new ItemNotCreatedException('TaskComment');
        }
    
        return $this->sendResponse(new TaskCommentResource($taskComment), 'taskComment created successfully.');
    }

    /**
     * Show the specified resource.
     * @param TaskComment $taskComment
     * @return Response
     */
    public function show(TaskComment $taskComment)
    {
        return $this->sendResponse(new TaskCommentResource($taskComment), 'taskComment retrieved successfully.');    
    }

    /**
     * Update the specified resource in storage.
     * @param TaskComment $taskComment
     * @param UpdateTaskCommentRequest $request
     * @return Response
     */
    public function update(TaskComment $taskComment, UpdateTaskCommentRequest $request)
    {
        $input = $request->validated();

        $taskComment->updated_at = Carbon::now();
        $taskComment->updated_by = auth()->user()->id;

        try {
            $updated = $taskComment->fill($input)->save();
        } catch (\Exception $ex) {
            throw new ItemNotUpdatedException('taskComment');
        }    

        if (!$updated)
        throw new ItemNotUpdatedException('taskComment');

        return $this->sendResponse(new TaskCommentResource($taskComment), 'taskComment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param TaskComment $taskComment
     * @param DeleteTaskCommentRequest $request
     * @return Response
     */
    public function destroy(TaskComment $taskComment, DeleteTaskCommentRequest $request)
    {
        try {
            $taskComment->delete();
        } catch (\Exception $ex) {
            throw new ItemNotDeletedException('taskComment');
        }
      
        return $this->sendResponse(new TaskCommentResource($taskComment), 'taskComment deleted successfully.');
    }

    /**
     * Display a listing of the resource by TaskId
     * @param $taskId
     * @return Response
     */
    public function getCommentsPerTask($taskId)
    {
        $taskComments = TaskComment::with('creator', 'updater')->where('task_id', $taskId)
                                       ->paginate();

        return $this->sendResponse(new TaskCommentCollection($taskComments), 'taskCommenties retrieved successfully.');
    }
}
