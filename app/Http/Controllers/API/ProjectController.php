<?php 

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Project;
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
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string',
      'description' => 'required|string',
      'owner_id' => 'required|integer|exists:users,id',
      'task_rate' => 'required|integer',
      'budget_hours' => 'required|integer',
    ]);

    if($validator->fails()){
       return $this->sendError('Validation Error.', $validator->errors());      
    }

    $input = $request->all();
    $input['created_at'] = Carbon::now();
    $input['created_by'] = auth()->user()->id;

    $project = Project::create($input);

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
  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'string',
      'description' => 'string',
      'owner_id' => 'integer|exists:users,id',
      'task_rate' => 'integer',
      'budget_hours' => 'integer',
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
  
}

?>