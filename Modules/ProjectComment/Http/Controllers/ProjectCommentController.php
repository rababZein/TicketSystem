<?php

namespace Modules\ProjectComment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\ProjectComment\Entities\ProjectComment;

use Modules\ProjectComment\Http\Requests\AddProjectCommentRequest;
use Modules\ProjectComment\Http\Requests\UpdateProjectCommentRequest;
use Modules\ProjectComment\Http\Requests\DeleteProjectCommentRequest;

use Modules\ProjectComment\Http\Resources\ProjectCommentResource;
use Modules\ProjectComment\Http\Resources\ProjectCommentCollection;

use App\Http\Controllers\API\BaseController;

use App\Exceptions\InvalidDataException;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\ItemNotDeletedException;

use Carbon\Carbon;

class ProjectCommentController extends BaseController
{
        
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:projectComment-list|projectComment-create|projectComment-edit|projectComment-delete', ['only' => ['index', 'show', 'getProjectCommentsPerProject']]);
        $this->middleware('permission:projectComment-create', ['only' => ['store']]);
        $this->middleware('permission:projectComment-edit', ['only' => ['update']]);
        $this->middleware('permission:projectComment-delete', ['only' => ['destroy']]);
    }
     /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $projectComments = ProjectComment::paginate();

        return $this->sendResponse(new ProjectCommentCollection($projectComments), 'ProjectCommenties retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     * @param AddProjectCommentRequest $request
     * @return Response
     */
    public function store(AddProjectCommentRequest $request)
    {
        $input = $request->validated();
        $input['created_at'] = Carbon::now();
        $input['created_by'] = auth()->user()->id;
    
        try {
          $projectComment = ProjectComment::create($input);
        } catch (Exception $ex) {
          throw new ItemNotCreatedException('ProjectComment');
        }
    
        return $this->sendResponse(new ProjectCommentResource($projectComment), 'ProjectComment created successfully.');
    }

    /**
     * Show the specified resource.
     * @param ProjectComment $projectComment
     * @return Response
     */
    public function show(ProjectComment $projectComment)
    {
        return $this->sendResponse(new ProjectCommentResource($projectComment), 'ProjectComment retrieved successfully.');    
    }

    /**
     * Update the specified resource in storage.
     * @param ProjectComment $projectComment
     * @param UpdateProjectCommentRequest $request
     * @return Response
     */
    public function update(ProjectComment $projectComment, UpdateProjectCommentRequest $request)
    {
        $input = $request->validated();

        $projectComment->updated_at = Carbon::now();
        $projectComment->updated_by = auth()->user()->id;

        try {
            $updated = $projectComment->fill($input)->save();
        } catch (\Exception $ex) {
            throw new ItemNotUpdatedException('ProjectComment');
        }    

        if (!$updated)
        throw new ItemNotUpdatedException('ProjectComment');

        return $this->sendResponse(new ProjectCommentResource($projectComment), 'ProjectComment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param ProjectComment $projectComment
     * @param DeleteProjectCommentRequest $request
     * @return Response
     */
    public function destroy(ProjectComment $projectComment, DeleteProjectCommentRequest $request)
    {
        try {
            $projectComment->delete();
        } catch (\Exception $ex) {
            throw new ItemNotDeletedException('ProjectComment');
        }
      
        return $this->sendResponse(new ProjectCommentResource($projectComment), 'ProjectComment deleted successfully.');
    }

    /**
     * Display a listing of the resource by ProjectId
     * @param $projectId
     * @return Response
     */
    public function getProjectCommentsPerProject($projectId)
    {
        $projectComments = ProjectComment::where('project_id', $projectId)
                                       ->paginate();

        return $this->sendResponse(new ProjectCommentCollection($projectComments), 'ProjectCommenties retrieved successfully.');
    }
}
