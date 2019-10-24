<?php

namespace App\Http\Requests\ReceiptRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Receipt;
use App\Exceptions\ItemNotFoundException;

class UpdateReceiptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // who can update ??

        // 1- admin
        if (auth()->user()->isAdmin()) {
            return true;
        }

        $receipt_id =$this->route('receipt');
        $receipt = Receipt::find($receipt_id);

        if (!$receipt) {
            throw new ItemNotFoundException($receipt_id);
        }

        // 2- creaor
        if ($receipt->created_by == auth()->user()->id) {
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
            'name' => 'string',
            'description' => 'string',
            'task_id' => 'integer|exists:tasks,id',
            'total' => 'numeric|min:0',
            'is_paid' => 'boolean',
        ];
    }
}
