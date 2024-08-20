<?php

namespace App\Rules;

use App\Models\Project;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class is_same_user implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
       ;


        if(!(Auth::user()->projects()->where('projects.id', $value)->exists())){
            $fail('you are not authorized to perform this action');
        }
    }
}
