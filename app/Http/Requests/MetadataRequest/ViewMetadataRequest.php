<?php

namespace App\Http\Requests\MetadataRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class ViewMetadataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user_id =$this->route('user');
        $owner = User::find($user_id);

        if (auth()->user()->isAdmin() || auth()->user()->can('user-delete') || $owner->id == auth()->user()->id) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
