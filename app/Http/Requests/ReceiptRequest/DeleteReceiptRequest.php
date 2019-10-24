<?php

namespace App\Http\Requests\ReceiptRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Receipt;
use App\Exceptions\ItemNotFoundException;

class DeleteReceiptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // who can delete receipt ?

        // 1- admin
        if (auth()->user()->isAdmin()) {
            return true;
        }

        $receipt_id =$this->route('receipt_id');
        $receipt = Receipt::find($receipt_id);

        if (!$receipt) {
            throw new ItemNotFoundException($receipt_id);
        }

        // 2- creator
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
            //
        ];
    }
}
