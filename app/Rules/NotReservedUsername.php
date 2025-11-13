<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotReservedUsername implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /* if (stripos($value, 'superadmin' !== false) || stripos($value, 'admin' !== false)) {
            $fail('El nombre de usuario no puede contener "superadmin" o "admin".');
        } */
       $lowerValue = strtolower($value);
        $forbiddenWords = ['superadmin', 'admin'];
        
        foreach ($forbiddenWords as $word) {
            if ($lowerValue === strtolower($word) || stripos($value, $word) !== false) {
                $fail('El nombre de usuario no puede contener "superadmin" o "admin".');
                break;
            }
        }
    }
}
