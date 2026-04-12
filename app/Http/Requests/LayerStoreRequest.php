<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LayerStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Assume 'parent_id' is passed in the request or exists on the route
        return [
            'layup_id' => ['required','integer','exists:layups,id'],
            'layer_order' => ['required','integer',
            // Decline if have same order as other layer, can just update the other layer
                Rule::unique('layers','layer_order')->where(function ($query){
                    return $query->where('layup_id', $this->layup_id);
                })
            ],
            'thickness' => 'required|decimal:1,2',
            'width' => 'required|decimal:1,2',
            'angle' => 'required|decimal:1,2'
        ];
    }
}
