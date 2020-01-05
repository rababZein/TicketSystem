<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\UserRequest\AddUserRequest;
use App\Http\Requests\UserRequest\UpdateUserRequest;
use App\Http\Requests\UserRequest\ViewUserRequest;
use App\Http\Requests\UserRequest\DeleteUserRequest;
use App\Http\Requests\UserRequest\ListUserRequest;
use App\Http\Controllers\API\BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\ItemNotFoundException;
use App\Exceptions\ItemNotDeletedException;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use Carbon\Carbon;

class UsersController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'list', 'getClients', 'getAllResponsibles']]);
        $this->middleware('permission:user-create', ['only' => ['store']]);
        $this->middleware('permission:user-edit', ['only' => ['update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Retrive all users paginated.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListUserRequest $request)
    {
        $users = User::with('roles')->paginate();
        return $this->sendResponse(new UserCollection($users), 'users retrieved successfully.');
    }

    /**
     * Retrive all clients paginated.
     *
     * @return \Illuminate\Http\Response
     */
    public function getClientsPaginated(ListUserRequest $request)
    {
        $users = User::where('type', 'client')->with('roles')->latest()->paginate();
        return $this->sendResponse(new UserCollection($users), 'clients retrieved successfully.');
    }

    /**
     * Retrive all employees paginated.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEmployeesPaginated(ListUserRequest $request)
    {
        $input = $request->validated()['params'];  

        $users = User::where('type', 'regular-user')->with('roles');

        // global search
        if (isset($input['global_search']) && $input['global_search']) {
            // to be all between ()
            $users->where(function($query) use ($input){
            // in direct relation
            $query->whereHas('roles', function($query) use($input) {
                $query->where('name', 'like', '%'.$input['global_search'].'%');
            });
            // direct relation
            $query->orWhere('users.name','LIKE','%'.$input['global_search'].'%');
            $query->orWhere('users.email','LIKE','%'.$input['global_search'].'%');
            $query->orWhere('users.type','LIKE','%'.$input['global_search'].'%');
            $query->orWhere('users.created_at','LIKE','%'.$input['global_search'].'%');
            });
        }

        // sorting
        if (isset($input['sort']) && $input['sort']) {
            foreach ($input['sort'] as $sortObj) {
            //direct relation then in-direct relation
            if (in_array($sortObj['name'], ['created_at', 'name', 'type', 'email'])) {
                if ($sortObj['order'] == 'desc') {
                $users->latest($sortObj['name']);
                } else {
                $users->oldest($sortObj['name']);
                }
                // indirect relation
            } elseif ($sortObj['name'] == 'roles.name') {
                $users->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id');
                $users->join('roles', 'model_has_roles.role_id', '=', 'roles.id');
                $users->orderBy('roles.name', $sortObj['order']);
            }
            }
        }

        // filter 
        if (isset($input['filters']) && $input['filters']) {
            foreach ($input['filters'] as $filterObj) {
                // first type of filter
                if ($filterObj['type'] == 'simple') {
                    if (in_array($filterObj['name'], ['name', 'email', 'type', 'created_at'])) {
                    $users->where('users.'.$filterObj['name'],'LIKE','%'.$filterObj['text'].'%');
                    } elseif ($filterObj['name'] == 'roles.name') {
                        $users->whereHas('roles', function($query) use($filterObj) {
                            $query->where('name', 'like', '%'.$filterObj['text'].'%');
                        });
                    } 
                } 
            }
        }

        $users->select('users.*');
        $users = $users->latest()->paginate();
        return $this->sendResponse(new UserCollection($users), 'employees retrieved successfully.');
    }

    /**
     * Display a data listing of the resource.
     *
     * @return Response
     */
    public function getClients(ListUserRequest $request)
    {
        $clients = User::where('type', 'client')->get();

        return $this->sendResponse(UserResource::collection($clients), 'Clients retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddUserRequest $request)
    {
        $input = $request->validated();
        $input['password'] = Hash::make($input['password']);
        $input['created_at'] = Carbon::now();
        $input['created_by'] = auth()->user()->id;

        try {
            $user = User::create($input);
        } catch (\Throwable $th) {
            throw new ItemNotCreatedException('User');
        }

        // save User
        $user->save();

        return $this->sendResponse(new UserResource($user), 'users created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(User $user, ViewUserRequest $request)
    {
        $user->metadata;
        return $this->sendResponse(new UserResource($user), 'User retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            throw new ItemNotFoundException($id);
        }

        $input = $request->validated();

        $user->updated_at = Carbon::now();
        $user->updated_by = auth()->user()->id;
        if (isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        $user = $user->fill($input);

        // save User
        try {
            $user->save();
        } catch (Exception $th) {
            throw new ItemNotUpdatedException('User');
        }

        return $this->sendResponse($user->toArray(), 'users updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteUserRequest $request, $id)
    {
        // delete user
        $user = User::find($id);
        if (is_null($user)) {
            throw new ItemNotFoundException($id);
        }

        try {
            $user->delete();
        } catch (\Throwable $th) {
            throw new ItemNotDeletedException('Role');
        }

        return $this->sendResponse(new UserResource($user), 'users deleted successfully.');
    }

    public function getAllResponsibles(ListUserRequest $request)
    {
        $users = User::where('type', 'regular-user')->get();

        return $this->sendResponse(UserResource::collection($users), 'Users retrieved successfully.');
    }
}
