<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HousingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:1|max:255',
            'type' => 'required',
            'floor' => 'required',
            'orientation' => 'required',
            'bedrooms' => 'required|min:0',
            'bathrooms' => 'required|min:0',
            'surface' => 'required|min:0',
            'galery' => 'nullable',
            'description' => 'nullable',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'    => 'Le nom est requis',
            'type.required'    => 'Le type est requis',
            'floor.required'    => 'L\'étage est requis',
            'orientation.required'    => 'L\'orientation est requise',
            'bedrooms.required'    => 'Le nombre de chambres est requis',
            'bathrooms.required'    => 'Le nombre de salles de bain est requis',
            'surface.required'    => 'La surface est requise',
            'bedrooms.min'    => 'Le nombre de chambres doit être supérieur ou égal à 0',
            'bathrooms.min'    => 'Le nombre de salles de bain doit être supérieur ou égal à 0',
        ];
    }
}
