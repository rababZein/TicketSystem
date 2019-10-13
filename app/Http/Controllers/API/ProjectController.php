<?php 

namespace App\Http\Controllers\API;

use App\Http\Requests\ProjectRequest\AddProjectRequest;
use App\Http\Requests\ProjectRequest\UpdateProjectRequest;
use App\Models\Project;
use App\Models\User;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\API\BaseController;

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

    return $this->sendResponse($projects->toArray(), 'Projects retrieved successfully.');
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

    return $this->sendResponse($projects->toArray(), 'Projects retrieved successfully.');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(AddProjectRequest $request)
  {
    if($validator->fails()){
       return $this->sendError('Validation Error.', $validator->errors());      
    }

    $input = $request->validated();
    $input['created_at'] = Carbon::now();
    $input['created_by'] = auth()->user()->id;

    $project = Project::create($input);

    // assign people to project
    $employees = User::find($input['project_assign']);
    $project->assigns()->attach($employees);
    $project->assigns;

    return $this->sendResponse($project->toArray(), 'Project created successfully.');
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

    return $this->sendResponse($project->toArray(), 'Project retrieved successfully.');    
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(UpdateProjectRequest $request, $id)
  {
    if($validator->fails()){
        return $this->sendError('Validation Error.', $validator->errors());       
    }

    $project = Project::find($id);
    
    if (!$project) {
        return $this->sendError('Not found Error.', 'Sorry, project with id ' . $id . ' cannot be found', 400);
    }

    $input = $request->validated();

    $project->updated_at = Carbon::now();
    $project->updated_by = auth()->user()->id;

    $updated = $project->fill($input)->save();

    // update assign people
    if (isset($input['project_assign'])) {
      $employees = User::find($input['project_assign']);
      $project->assigns()->sync($employees);
      $project->assigns;
    }

    if (!$updated)
      return $this->sendError('Not update!.', 'Sorry, project could not be updated', 500);

    return $this->sendResponse($project->toArray(), 'Project updated successfully.');    
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

    return $this->sendResponse($project->toArray(), 'Project deleted successfully.');
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
