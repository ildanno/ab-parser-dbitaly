<?php

namespace Antbank\AccountBalanceParser\DbItaly\Parser\Strategy;

use Antbank\AccountBalanceReader\Parser\ParserResponse;
use Antbank\AccountBalanceParser\DbItaly\Transaction\DeutscheBankCreditCardTransaction;

class MovimentoAnnualeStrategy implements StrategyInterface
{

    public function parse(\Iterator $iterator): ParserResponse
    {
        $pattern = sprintf(
            '/^(%s) (%s) (%s) (.*) (%s)(-?)$/',
            StrategyInterface::PATTERN_CODE,
            StrategyInterface::PATTERN_DATE,
            StrategyInterface::PATTERN_DATE,
            StrategyInterface::PATTERN_AMOUNT
        );

        $line = $iterator->current();

        if (!preg_match($pattern, $line, $matches)) {
            return new ParserResponse($iterator, null, false);
        }

        $transaction = new DeutscheBankCreditCardTransaction();
        $transaction->setCodiceRiferimento($matches[1])
            ->setDataAcquisto(\DateTime::createFromFormat('d/m/Y', $matches[3]))
            ->setDataRegistrazione(\DateTime::createFromFormat('d/m/Y', $matches[4]))
            ->setDescrizioneOperazioni($matches[5]);

        $importo = str_replace(['.', ','], ['', '.'], $matches[6]);
        if ($matches[7] === '-') {
            $transaction->setPagamenti($importo);
        } else {
            $transaction->setAcquisti($importo);
        }

        $iterator->next();

        return new ParserResponse($iterator, $transaction, true);
    }
}
