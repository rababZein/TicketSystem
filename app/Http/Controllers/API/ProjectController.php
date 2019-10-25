<?php 

namespace App\Http\Controllers\API;

use App\Http\Requests\ProjectRequest\AddProjectRequest;
use App\Http\Requests\ProjectRequest\UpdateProjectRequest;
use App\Models\Project;
use App\Models\User;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\API\BaseController;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\InvalidDataException;
use App\Exceptions\ItemNotFoundException;
use App\Exceptions\ItemNotDeletedException;
use App\Http\Resources\Project\ProjectCollection;
use App\Http\Resources\Project\ProjectResource;
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
    $projects = Project::with('owner')->paginate();

    return $this->sendResponse(new ProjectCollection($projects), 'Projects retrieved successfully.');
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
  public function store(AddProjectRequest $request)
  {
    $input = $request->validated();
    $input['created_at'] = Carbon::now();
    $input['created_by'] = auth()->user()->id;

    try {
      $project = Project::create($input);
    } catch (Exception $ex) {
      throw new ItemNotCreatedException('Project');
    }

    // assign people to project
    $employees = User::find($input['project_assign']);
    $project->assigns()->attach($employees);
    $project->assigns;

    \Notification::send($employees, new ProjectAssign($project));

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
    $project = Project::with('tickets')->find($id);

    if (is_null($project)) {
      throw new ItemNotFoundException($id);
    }

    return $this->sendResponse(new ProjectResource($project), 'Project retrieved successfully.');    
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(UpdateProjectRequest $request, $id)
  {
    $project = Project::find($id);
    
    if (!$project) {
      throw new ItemNotFoundException($id);
    }

    $input = $request->validated();

    $project->updated_at = Carbon::now();
    $project->updated_by = auth()->user()->id;

    try {
      $updated = $project->fill($input)->save();
    } catch (\Throwable $th) {
      throw new ItemNotUpdatedException('Project');
    }

    // update assign people
    if (isset($input['project_assign'])) {
      $employees = User::find($input['project_assign']);
      $project->assigns()->sync($employees);
      $project->assigns;

      Notification::send($employees, new ProjectAssign($project));
    }

    if (!$updated)
      throw new ItemNotUpdatedException('Project');

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
      throw new ItemNotFoundException($id);
    }

    if($project->tickets->isNotEmpty()) {
      throw new InvalidDataException([
        'tickets' => $project->tickets->toArray()
      ],
      'Can\'t delete!, Project has ticket.');
    }

    if($project->tasks->isNotEmpty()) {
      throw new InvalidDataException([
        'tasks' => $project->tasks->toArray()
      ],
      'Can\'t delete!, Project has tasks.');
    }

    try {
      $project->delete();
    } catch (\Throwable $th) {
      throw new ItemNotDeletedException('Project');
    }

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
