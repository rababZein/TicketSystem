<?php

namespace App\Http\Requests\TrackingRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Task;
use App\Exceptions\ItemNotFoundException;

class TimeReportingTrackingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->isAdmin() || auth()->user()->can('tracking_task-list')) {
            return true;
        }

        return false;
    }

    /**
     * Inject GET parameter "type" into validation data
     *
     * @param array $keys Properties to only return
     *
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
        $data['employee_id'] = $this->get('employee_id');
        $data['project_id'] = $this->get('project_id');

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
            'from_date' => 'required|date_format:"Y-m-d"',
            'to_date' => 'required|date_format:"Y-m-d"',
            'employee_id' => 'nullable|integer|exists:users,id',
            'project_id' => 'nullable|integer|exists:projects,id'
        ];
    }
}
