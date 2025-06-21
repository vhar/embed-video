<?php

namespace Vhar\EmbedVideo\Rules;

use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Vhar\EmbedVideo\Facades\EmbedVideo as EmbedVideoFacade;


class EmbedVideoRule implements ValidationRule
{
    /**
     * Запустить правило проверки.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            EmbedVideoFacade::handle($value);
        } catch (Exception $exception) {
            $fail(':attribute ' . $exception->getMessage());
        }
    }
}
