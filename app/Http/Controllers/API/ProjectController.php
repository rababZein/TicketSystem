<?php 

namespace App\Http\Controllers\API;

use App\Http\Requests\ProjectRequest\AddProjectRequest;
use App\Http\Requests\ProjectRequest\UpdateProjectRequest;
use App\Http\Requests\ProjectRequest\ViewProjectRequest;
use App\Http\Requests\ProjectRequest\DeleteProjectRequest;
use App\Http\Requests\ProjectRequest\ListProjectRequest;
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

class ProjectController extends BaseController 
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('permission:project-list|project-create|project-edit|project-delete', ['only' => ['index', 'getAllByOwner', 'list']]);
      $this->middleware('permission:project-create', ['only' => ['store']]);
      $this->middleware('permission:project-edit', ['only' => ['update']]);
      $this->middleware('permission:project-delete', ['only' => ['destroy']]);
      $this->middleware('permission:project-list', ['only' => ['getProjectsCountPerClient', 'getProjectPerClient']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

  public function index(ListProjectRequest $request)
  {
    $input = $request->validated()['params']; 

    if (auth()->user()->isAdmin()) {
      $projects = Project::with('owner');
    } else {
      $projectModel = new Project();
      $projects = $projectModel->ownProjects(auth()->user()->id);
    }

    // global search
    if (isset($input['global_search']) && $input['global_search']) {
      // to be all between ()
      $projects->where(function($query) use ($input){
        // in direct relation
        $query->orWhereHas('owner', function($query) use($input) {
          $query->where('name', 'like', '%'.$input['global_search'].'%');
        });
        // direct relation
        $query->orWhere('name','LIKE','%'.$input['global_search'].'%');
      });
    }

    $projects->latest();
    $projects = $projects->paginate();

    return $this->sendResponse(new ProjectCollection($projects), 'Projects retrieved successfully.');
  }

  public function list(ListProjectRequest $request)
  {
    $projects = Project::all();

    return $this->sendResponse(ProjectResource::collection($projects), 'Projects retrieved successfully.');
  }


  /**
   * Display a data listing of the resource.
   *
   * @return Response
   */
  public function getAllByOwner(ListProjectRequest $request, $owner_id)
  {
    $projects = Project::with('tickets')->whereHas('owner', function ($query)  use ($owner_id) {
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

    return $this->sendResponse(new ProjectResource($project), 'Project created successfully.');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show(Project $project, ViewProjectRequest $request)
  {
    return $this->sendResponse(new ProjectResource($project), 'Project retrieved successfully.');    
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Project $project, UpdateProjectRequest $request)
  {
    $input = $request->validated();

    $project->updated_at = Carbon::now();
    $project->updated_by = auth()->user()->id;

    try {
      $updated = $project->fill($input)->save();
    } catch (\Exception $ex) {
      throw new ItemNotUpdatedException('Project');
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
  public function destroy(Project $project, DeleteProjectRequest $request)
  {
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
    } catch (\Exception $ex) {
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

  public function getProjectsCountPerClient($clientId)
  {
    $projectsNumber = Project::where('owner_id', $clientId)->count();

    return $this->sendResponse(['projectsNumber' => $projectsNumber], 'Projects Number retrieved successfully.');
  }

  public function getProjectsPerClient($clientId)
  {
    $projects = Project::where('owner_id', $clientId)->paginate();

    return $this->sendResponse(new ProjectCollection($projects), 'Projects retrieved successfully.');
  }
}
