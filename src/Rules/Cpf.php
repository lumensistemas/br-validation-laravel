<?php

declare(strict_types=1);

namespace LumenSistemas\BrValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Class Cpf.
 *
 * Validation rule to verify if a given value is a valid CPF (Cadastro de
 * Pessoas Físicas).
 *
 * @see https://dev.to/leandrostl/demystifying-cpf-and-cnpj-check-digit-algorithms-a-clear-and-concise-approach-f3j
 */
final class Cpf implements ValidationRule
{
    private const int CPF_LENGTH = 11;

    private const int FIRST_VERIFIER_POSITION = 9;

    private const int SECOND_VERIFIER_POSITION = 10;

    private const array FIRST_WEIGHTS = [10, 9, 8, 7, 6, 5, 4, 3, 2];

    private const array SECOND_WEIGHTS = [11, 10, 9, 8, 7, 6, 5, 4, 3, 2];

    /**
     * Run the validation rule.
     *
     * @param Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail('br-validation::validation.cpf')->translate();

            return;
        }

        $cpf = $this->removeMask($value);

        if (mb_strlen($cpf) !== self::CPF_LENGTH) {
            $fail('br-validation::validation.cpf')->translate();

            return;
        }

        if ($this->allDigitsAreEqual($cpf)) {
            $fail('br-validation::validation.cpf')->translate();

            return;
        }

        $firstVerifierDigit = $this->calculateVerifierDigit($cpf, self::FIRST_WEIGHTS);

        if ((int) $cpf[self::FIRST_VERIFIER_POSITION] !== $firstVerifierDigit) {
            $fail('br-validation::validation.cpf')->translate();

            return;
        }

        $secondVerifierDigit = $this->calculateVerifierDigit($cpf, self::SECOND_WEIGHTS);

        if ((int) $cpf[self::SECOND_VERIFIER_POSITION] !== $secondVerifierDigit) {
            $fail('br-validation::validation.cpf')->translate();
        }
    }

    /**
     * Leaves only digits in the CPF.
     */
    private function removeMask(string $cpf): string
    {
        return preg_replace('/\D/', '', $cpf) ?? '';
    }

    /**
     * Check if all digits in the CPF are equal (e.g. 000.000.000-00).
     */
    private function allDigitsAreEqual(string $cpf): bool
    {
        return mb_substr_count($cpf, $cpf[0]) === self::CPF_LENGTH;
    }

    /**
     * Calculate a verifier digit of the CPF.
     *
     * @param array<int, int> $weights
     */
    private function calculateVerifierDigit(string $cpf, array $weights): int
    {
        $sum = 0;

        foreach ($weights as $i => $weight) {
            $sum += (int) $cpf[$i] * $weight;
        }

        $remainder = $sum % 11;

        return ($remainder < 2) ? 0 : (11 - $remainder);
    }
}
