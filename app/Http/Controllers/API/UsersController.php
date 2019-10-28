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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListUserRequest $request)
    {
        return view('pages.users.index');
    }

    public function list(ListUserRequest $request)
    {
        $users = UserResource::collection(User::with('roles')->paginate(10));
        return $this->sendResponse(UserResource::collection($users), 'users retrieved successfully.');
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
        $users = User::where('type','regular-user')->get();

        return $this->sendResponse(UserResource::collection($users), 'Users retrieved successfully.');
    }
}
