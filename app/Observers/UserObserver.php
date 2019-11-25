<?php

namespace App\Observers;

use App\Models\User;
use \Illuminate\Http\Request;
use App\Jobs\User\NewAccountJob;

class UserObserver
{
    private $input;

    public function __construct(Request $request)
    {
        $this->input = $request->all();
    }

    /**
     * Handle the user "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        if (isset($this->input['roles'])) {
            $user->assignRole($this->input['roles']);
        }

        NewAccountJob::dispatch($user, $this->input['password']);
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        if (isset($this->input['roles'])) {
            $user->syncRoles($this->input['roles']);
        }
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        $user->roles()->detach();
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
