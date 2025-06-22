<?php

namespace App\Modules\Translation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Import Rule class for advanced validation

class CreateTranslationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Typically, you would add authorization logic here.
        // For example, check if the authenticated user has permission to create translations.
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
        return [
            'group' => [
                'required', // Group is required when creating a new translation
                'string',
                'max:255',
            ],
            'key' => [
                'required', // Key is required
                'string',
                'max:255',
                // IMPORTANT NOTE ON COMPOSITE UNIQUE KEY:
                // The database likely has a UNIQUE constraint on (group_id, key, language_code).
                // Laravel's built-in `unique` rule does not directly support composite keys out-of-the-box for validation.
                // You would typically handle this uniqueness check in your TranslationService
                // (e.g., trying to find an existing translation with the given group, key, and locale before creating).
                // Alternatively, you could implement a custom validation rule for this specific composite uniqueness.
                // For this example, we omit a direct `unique` rule here to prevent incorrect validation
                // if not handled properly for the composite nature.
            ],
            'value' => [
                'required', // Value is required for a new translation
                'nullable', // Allows explicitly passing null if your database supports it
                'string',   // For simple string values
                // If 'value' is always expected to be JSON, use:
                // 'json',
            ],
            'locale' => [
                'required', // Locale is required
                'string',
                'max:10', // e.g., 'en', 'fr-CA', 'zh-Hans'
                Rule::exists('languages', 'code'), // Ensure the locale exists in your languages table
            ],
            'tags' => [
                'sometimes', // Tags are optional when creating
                'array',
                'nullable',  // Allow null or empty array if no tags are provided
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
            'group.required' => 'The translation group is required.',
            'group.string' => 'The translation group must be a string.',
            'group.max' => 'The translation group may not be greater than :max characters.',
            'key.required' => 'The translation key is required.',
            'key.string' => 'The translation key must be a string.',
            'key.max' => 'The translation key may not be greater than :max characters.',
            'value.required' => 'The translation value is required.',
            'value.string' => 'The translation value must be a string.',
            'value.json' => 'The translation value must be a valid JSON string.', // If you add 'json' rule
            'locale.required' => 'The locale is required.',
            'locale.string' => 'The locale must be a string.',
            'locale.max' => 'The locale may not be greater than :max characters.',
            'locale.exists' => 'The selected locale is invalid or not supported.',
            'tags.array' => 'The tags must be an array.',
            'tags.*.string' => 'Each tag name must be a string.',
            'tags.*.max' => 'Each tag name may not be greater than :max characters.',
        ];
    }
}
