<?php

namespace App\Modules\Translation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Import Rule class for advanced validation

class UpdateTranslationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Typically, you would add authorization logic here.
        // For example, check if the authenticated user has permission to update translations.
        // For now, we'll return true to allow the request to proceed.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // The ID of the translation being updated is available via $this->route('translation')
        // if your route is defined as Route::apiResource('translations', TranslationController::class)
        // and the route parameter is named 'translation'.
        // Otherwise, it might be $this->route('id') or similar, depending on your route definition.
        $translationId = $this->route('translation') ?? $this->route('id');


        return [
            'group' => [
                'sometimes', // Field is optional for update
                'required', // If present, it must not be empty
                'string',
                'max:255',
            ],
            'key' => [
                'sometimes', // Field is optional for update
                'required', // If present, it must not be empty
                'string',
                'max:255',
                // For uniqueness on update: This is complex for composite unique keys.
                // If you update group or locale AND key, it needs to be unique with the NEW group/locale.
                // It's often easier to handle the composite uniqueness check in the Service layer
                // after resolving group_id and language_code, or use custom validation.
                // A simple `unique` rule here would only check the 'key' column by itself,
                // which might not be what you want for a composite index.
                // For now, we'll omit a simple 'unique' rule here to avoid incorrect behavior
                // for composite uniqueness.
            ],
            'value' => [
                'sometimes', // Field is optional for update
                'nullable', // Allow null for value if needed
                'string',    // For simple string values
                // You might add specific validation if 'value' is always JSON:
                // 'json',
            ],
            'locale' => [
                'sometimes', // Field is optional for update
                'required', // If present, it must not be empty
                'string',
                'max:10', // e.g., 'en', 'fr-CA'
                Rule::exists('languages', 'code'), // Ensure the locale exists in your languages table
            ],
            'tags' => [
                'sometimes', // Field is optional for update
                'array',    // Must be an array if present
                'nullable', // Allow null or empty array if no tags are provided
            ],
            'tags.*' => [
                'string',   // Each item in the tags array must be a string
                'max:50',   // Max length for each tag name
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'group.string' => 'The translation group must be a string.',
            'group.max' => 'The translation group may not be greater than :max characters.',
            'key.string' => 'The translation key must be a string.',
            'key.max' => 'The translation key may not be greater than :max characters.',
            'value.string' => 'The translation value must be a string.',
            'value.json' => 'The translation value must be a valid JSON string.', // If you add 'json' rule
            'locale.string' => 'The locale must be a string.',
            'locale.max' => 'The locale may not be greater than :max characters.',
            'locale.exists' => 'The selected locale is invalid or not supported.',
            'tags.array' => 'The tags must be an array.',
            'tags.*.string' => 'Each tag name must be a string.',
            'tags.*.max' => 'Each tag name may not be greater than :max characters.',
        ];
    }
}
