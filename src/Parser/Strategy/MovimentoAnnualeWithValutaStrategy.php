<?php

namespace Antbank\AccountBalanceParser\DbItaly\Parser\Strategy;

use Antbank\AccountBalanceParser\DbItaly\Parser\ParserResponse;
use Antbank\AccountBalanceParser\DbItaly\Transaction\Transaction;

class MovimentoAnnualeWithValutaStrategy implements StrategyInterface
{

    public function parse(\Iterator $iterator): ParserResponse
    {
        $startingKey = $iterator->key();

        $patternMovimento = sprintf(
            '/^(%s) (%s) (%s) (.+) (%s) (\w{3})$/i',
            StrategyInterface::PATTERN_CODE,
            StrategyInterface::PATTERN_DATE,
            StrategyInterface::PATTERN_DATE,
            StrategyInterface::PATTERN_AMOUNT
        );

        $patternCambio = sprintf(
            '/^Cambio (%s). Commissione per tasso di cambio (%s) (%s)$/i',
            StrategyInterface::PATTERN_AMOUNT,
            StrategyInterface::PATTERN_AMOUNT,
            StrategyInterface::PATTERN_AMOUNT
        );

        $matchesLine1 = [];
        $matchesLine2 = [];

        if (!preg_match($patternMovimento, $iterator->current(), $matchesLine1)) {
            return new ParserResponse($iterator, null, false);
        }

        $iterator->next();
        if (!preg_match($patternCambio, $iterator->current(), $matchesLine2)) {
            $iterator->rewind();
            while ($iterator->key() !== $startingKey) {
                $iterator->next();
            }
            return new ParserResponse($iterator, null, false);
        }

        $transaction = new Transaction();
        $transaction->setCodiceRiferimento($matchesLine1[1])
            ->setDataAcquisto(\DateTime::createFromFormat('d/m/Y', $matchesLine1[3]))
            ->setDataRegistrazione(\DateTime::createFromFormat('d/m/Y', $matchesLine1[4]))
            ->setDescrizioneOperazioni($matchesLine1[5])
            ->setValuta($matchesLine1[7])
            ->setImportoValuta(str_replace(['.', ','], ['', '.'], $matchesLine1[6]))
            ->setTassoCambio(str_replace(['.', ','], ['', '.'], $matchesLine2[1]))
            ->setCommissioniCambio(str_replace(['.', ','], ['', '.'], $matchesLine2[2]))
            ->setAcquisti(str_replace(['.', ','], ['', '.'], $matchesLine2[3]));

        $iterator->next();
        return new ParserResponse($iterator, $transaction, true);
    }
}
