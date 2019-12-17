<?php

namespace App\Http\Requests\TaskRequest;

use Illuminate\Foundation\Http\FormRequest;

class ListTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Inject GET parameters into validation data
     *
     * @param array $keys Properties to only return
     *
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data = json_decode($this->get('queryParams'), true);

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "sort" => "required|array",
            "sort.*.name" => "string",
            "sort.*.order" => "string",
            "filters"  => "required|array",
            "filters.*.type" => "string",
            "filters.*.mode" => "string",
            "filters.*.selected_options" => "string",
            "filters.*.name" => "string",
            "filters.*.text" => "string",
            "global_search" => "required|string",
            "per_page" => "required|integer",
            "page" => "required|integer",

        ];
    }
}
