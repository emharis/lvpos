<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PurchaseOrderRequest extends Request
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'supplier_id' => 'required',
            'po_number' => 'required',
            'quotation' => 'mimes:jpeg,jpg,bmp,png,pdf,xls,doc,zip',
        ];
    }
}
