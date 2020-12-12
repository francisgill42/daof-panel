<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormValidation extends FormRequest
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

    "dob" => "date",
    "cnic" => 'min:13|max:20',
    "cell" => 'min:11|max:15',
    "soi" => "min:3|max:15|alpha",
    "mop_file.*" => 'mimes:jpeg,png,jpg',
    "funds.*" => 'distinct',


];
}
public function messages()
{
return [

    "dob.date" => "The date is not valid.",
    "mop_file.*.mimes" => "The Attached file must be a file of type: jpeg, png, jpg.",
    "soi.max" => "The Source of Income field may not be greater than 15 characters.",
    "funds.*.distinct" => 'The Fund Field has a duplicate value.',
    "fund_amount.*.numeric" => 'The Fund Amount Field must be a number.',
    "aiw.*.alpha" => "The Amount in Words may only contain letters.",


];
}
}
