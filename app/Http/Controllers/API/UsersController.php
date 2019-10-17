<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\UserRequest\AddUserRequest;
use App\Http\Requests\UserRequest\UpdateUserRequest;
use App\Http\Controllers\API\BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\ItemNotFoundException;
use App\Exceptions\ItemNotDeletedException;
use App\Http\Resources\UserResource;

class UsersController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'list']]);
        $this->middleware('permission:user-create', ['only' => ['store']]);
        $this->middleware('permission:user-edit', ['only' => ['update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.users.index');
    }

    public function list()
    {
        $users = UserResource::collection(User::paginate(10));
        return $this->sendResponse(UserResource::collection($users), 'users retrieved successfully.');
    }

     /**
     * Display a data listing of the resource.
     *
     * @return Response
     */
    public function getClients()
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
        $input['password'] = Hash::make($request->password);
        $input['created_at'] = Carbon::now();
        $input['created_by'] = auth()->user()->id;

        try {
            $user = User::create($input);
        } catch (\Throwable $th) {
            throw new ItemNotCreatedException('User');
        }

        // add role to user
        $user->assignRole($request->roles);

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
            $user->password = Hash::make($input['password']);
        }

        $user = $user->fill($input);

        if (isset($input['roles'])) {
            // add role to user
            $user->syncRoles($input['roles']);
        }

        // save User
        try {
            $user->save();
        } catch (\Throwable $th) {
            throw new ItemNotUpdatedException('Role');
        }
        
        return $this->sendResponse($user->toArray(), 'users updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete user
        $user = User::find($id);
        if (is_null($user)) {
            throw new ItemNotFoundException($id);
        }

        $user->roles()->detach();

        // delete role
        try {
            $user->delete();
        } catch (\Throwable $th) {
            throw new ItemNotDeletedException('Role');
        }

        return $this->sendResponse(new UserResource($user), 'users deleted successfully.');

    }

    public function getAllResponsibles()
    {
        $users = User::where('type','regular-user')->get();

        return $this->sendResponse(UserResource::collection($users), 'Users retrieved successfully.');
    }
}
