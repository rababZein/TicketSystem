<?php 

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\ProjectResource;
use App\Notifications\Project\ProjectAssign;

class ProjectController extends BaseController 
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('permission:project-list|project-create|project-edit|project-delete', ['only' => ['index']]);
      $this->middleware('permission:project-create', ['only' => ['store']]);
      $this->middleware('permission:project-edit', ['only' => ['update']]);
      $this->middleware('permission:project-delete', ['only' => ['destroy']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $projects = Project::all();

    return $this->sendResponse(ProjectResource::collection($projects), 'Projects retrieved successfully.');
  }


  /**
   * Display a data listing of the resource.
   *
   * @return Response
   */
  public function getAllByOwner($owner_id)
  {
    $projects = Project::whereHas('owner', function ($query)  use ($owner_id) {
      $query->where('owner_id','=', $owner_id);
    })->with('owner')->get();

    return $this->sendResponse(ProjectResource::collection($projects), 'Projects retrieved successfully.');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|unique:projects',
      'description' => 'required|string',
      'owner_id' => 'required|integer|exists:users,id',
      'task_rate' => 'required|integer',
      'budget_hours' => 'required|integer',
      'project_assign' => 'array',
      'project_assign.*' => 'integer|exists:users,id',
    ]);

    if($validator->fails()){
       return $this->sendError('Validation Error.', $validator->errors());      
    }

    $input = $request->all();
    $input['created_at'] = Carbon::now();
    $input['created_by'] = auth()->user()->id;

    $project = Project::create($input);

    // assign people to project
    $employees = User::find($input['project_assign']);
    $project->assigns()->attach($employees);
    $project->assigns;

    Notification::send($employees, new ProjectAssign($project));

    return $this->sendResponse(new ProjectResource($project), 'Project created successfully.');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    $project = Project::find($id);

    if (is_null($project)) {
        return $this->sendError('Project not found.');
    }

    return $this->sendResponse(new ProjectResource($project), 'Project retrieved successfully.');    
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
      'name' => 'string',
      'description' => 'string',
      'owner_id' => 'integer|exists:users,id',
      'task_rate' => 'integer',
      'budget_hours' => 'integer',
      'project_assign' => 'array',
      'project_assign.*' => 'integer|exists:users,id',
    ]);

    if($validator->fails()){
        return $this->sendError('Validation Error.', $validator->errors());       
    }

    $project = Project::find($id);
    
    if (!$project) {
        return $this->sendError('Not found Error.', 'Sorry, project with id ' . $id . ' cannot be found', 400);
    }

    $project->updated_at = Carbon::now();
    $project->updated_by = auth()->user()->id;

    $updated = $project->fill($request->all())->save();

    // update assign people
    $input = $request->all();
    if (isset($input['project_assign'])) {
      $employees = User::find($input['project_assign']);
      $project->assigns()->sync($employees);
      $project->assigns;

      Notification::send($employees, new ProjectAssign($project));
    }

    if (!$updated)
      return $this->sendError('Not update!.', 'Sorry, project could not be updated', 500);

    return $this->sendResponse(new ProjectResource($project), 'Project updated successfully.');    
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    $project = Project::find($id);

    if (is_null($project)) {
      return $this->sendError('Project not found.');
    }

    if($project->tasks->isNotEmpty() && $project->tickets->isNotEmpty()) {
      return $this->sendError('Can\'t delete!, Project has tasks/ticket.');
    }

    $project->delete();

    return $this->sendResponse(new ProjectResource($project), 'Project deleted successfully.');
  }


  /**
   * Search in projects.
   *
   * @param  int  $searchKey
   * @return Response
   */
  public function search($searchKey)
  {
    $project_model = new Project();
    $projects = $project_model->search($searchKey);
    
    return $this->sendResponse($projects->toArray(), 'Projects retrieved successfully.');
  
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function view()
  {
    return view('pages.projects.index');
  }
  
}
