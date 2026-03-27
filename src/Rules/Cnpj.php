<?php

declare(strict_types=1);

namespace LumenSistemas\BrValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Class Cnpj.
 *
 * Validation rule to verify if a given value is a valid CNPJ (Cadastro Nacional
 * da Pessoa Jurídica). Include support for the new alphanumeric CNPJs.
 *
 * @see https://www.gov.br/receitafederal/pt-br/centrais-de-conteudo/publicacoes/documentos-tecnicos/cnpj
 */
final class Cnpj implements ValidationRule
{
    private const int CNPJ_LENGTH = 14;

    private const int BASE_VALUE = 48;

    private const int FIRST_VERIFIER_POSITION = 12;

    private const int SECOND_VERIFIER_POSITION = 13;

    private const array FIRST_WEIGHTS = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    private const array SECOND_WEIGHTS = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    /**
     * Run the validation rule.
     *
     * @param Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail('br-validation::validation.cnpj')->translate();

            return;
        }

        $cnpj = $this->removeMask($value);

        if (mb_strlen($cnpj) !== self::CNPJ_LENGTH) {
            $fail('br-validation::validation.cnpj')->translate();

            return;
        }

        $firstVerifierDigit = $this->calculateVerifierDigit($cnpj, self::FIRST_WEIGHTS);

        if ((int) $cnpj[self::FIRST_VERIFIER_POSITION] !== $firstVerifierDigit) {
            $fail('br-validation::validation.cnpj')->translate();

            return;
        }

        $secondVerifierDigit = $this->calculateVerifierDigit($cnpj, self::SECOND_WEIGHTS);

        if ((int) $cnpj[self::SECOND_VERIFIER_POSITION] !== $secondVerifierDigit) {
            $fail('br-validation::validation.cnpj')->translate();
        }
    }

    /**
     * Leaves only alphanumeric characters in the CNPJ.
     */
    private function removeMask(string $cnpj): string
    {
        return preg_replace('/[^A-Z0-9]/', '', mb_strtoupper($cnpj)) ?? '';
    }

    /**
     * Calculate a verifier digit of the CNPJ.
     *
     * @param array<int, int> $weights
     */
    private function calculateVerifierDigit(string $cnpj, array $weights): int
    {
        $sum = 0;

        foreach ($weights as $i => $weight) {
            $sum += (ord($cnpj[$i]) - self::BASE_VALUE) * $weight;
        }

        $remainder = $sum % 11;

        return ($remainder < 2) ? 0 : (11 - $remainder);
    }
}
