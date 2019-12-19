<?php

namespace App\Http\Requests\TicketRequest;

use Illuminate\Foundation\Http\FormRequest;

class ListTicketRequest extends FormRequest
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
        $data['params'] = json_decode($this->get('queryParams'), true);

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
            "params" => "nullable|array",
            "params.sort" => "array",
            "params.sort.*.name" => "string",
            "params.sort.*.order" => "string",
            "params.filters"  => "array",
            "params.filters.*.type" => "string",
            "params.filters.*.mode" => "string",
            "params.filters.*.selected_options" => "string",
            "params.filters.*.name" => "string",
            "params.filters.*.text" => "string",
            "params.global_search" => "string",
            "params.per_page" => "integer",
            "params.page" => "integer",
        ];
    }
}
