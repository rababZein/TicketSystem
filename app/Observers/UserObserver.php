<?php

namespace App\Observers;

use App\Models\User;
use App\Models\DynamicAttribute;
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

        // no-need to send mail now, user will click forget password
        // if (isset($this->input['password'])) 
        //     NewAccountJob::dispatch($user, $this->input['password']);
      
        if (isset($this->input['dynamic_attributes'])) {
            foreach ($this->input['dynamic_attributes'] as $dynamic_attribute) {
                $dynamicAttributeObject = DynamicAttribute::find($dynamic_attribute['id']);
                $user->dynamicAttributes()->attach($dynamicAttributeObject,
                 [
                     'value' => $dynamic_attribute['value']
                 ]);
            } 
        }
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

        if (isset($this->input['dynamic_attributes'])) {
            $user->dynamicAttributes()->detach();
            foreach ($this->input['dynamic_attributes'] as $dynamic_attribute) {
                $dynamicAttributeObject = DynamicAttribute::find($dynamic_attribute['id']);
                $user->dynamicAttributes()->attach($dynamicAttributeObject,
                 [
                     'value' => $dynamic_attribute['value']
                 ]);
            } 
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
