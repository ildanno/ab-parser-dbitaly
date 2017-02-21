<?php

namespace Antbank\AccountBalanceParser\DbItaly\Parser\Strategy;

use Antbank\AccountBalanceParser\DbItaly\Parser\ParserResponse;
use Antbank\AccountBalanceParser\DbItaly\Transaction\Transaction;

class MovimentoMensileStrategy implements StrategyInterface
{

    public function parse(\Iterator $iterator): ParserResponse
    {
        if (!($iterator instanceof MovimentoMensileSkippableIterator)) {
            $iterator = new MovimentoMensileSkippableIterator($iterator);
        }

        /** @var MovimentoMensileSkippableIterator $iterator */

        $patterns = [
            sprintf('/^(%s) (%s)$/', '\d{23}', StrategyInterface::PATTERN_DATE),
            sprintf('/^(%s)$/', '\d{8}'),
            sprintf('/^(%s) (%s)$/', StrategyInterface::PATTERN_DATE, '.+'),
            sprintf('/^(%s)$/', StrategyInterface::PATTERN_AMOUNT),
        ];
        $patternsIterator = new \ArrayIterator($patterns);

        $result = new Transaction();
        $found = false;
        $iteratorSeekPoint = null;
        $dirtyRecords = $iterator->getSkipping();
        $skipped = 0;
        $startingKey = $iterator->key();

        while ($iterator->valid() && $patternsIterator->valid()) {

            $matches = [];
            if (preg_match($patternsIterator->current(), $iterator->current(), $matches)) {

                switch ($patternsIterator->key()) {
                    case 0:
                        $result->setDataAcquisto(\DateTime::createFromFormat('d/m/Y', $matches[2]));
                        $result->setCodiceRiferimento($matches[1]);

                        $found = true;
                        $patternsIterator->next();
                        $iterator->next();

                        preg_match($patternsIterator->current(), $iterator->current(), $matches);

                        $result->setCodiceRiferimento($result->getCodiceRiferimento() . ' ' . $matches[1]);
                        $patternsIterator->next();
                        break;

                    case 2:
                        if ($dirtyRecords === $skipped) {
                            $result->setDataRegistrazione(\DateTime::createFromFormat('d/m/Y', $matches[1]));
                            $result->setDescrizioneOperazioni($matches[2]);

                            $patternsIterator->next();
                            $iterator->next();

                            while ($iterator->valid() && $iterator->current() === '') {
                                $iterator->next();
                            }

                            if (preg_match($patternsIterator->current(), $iterator->current(), $matches)) {
                                $result->setAcquisti(str_replace(['.', ','], ['', '.'], $matches[1]));
                                $patternsIterator->next();
                            }
                        }

                        ++$skipped;

                        break;
                }

                $iterator->next();
            } elseif ($found) {
                $iteratorSeekPoint = $iteratorSeekPoint ?? $iterator->key();
                $iterator->next();
            } else {
                break;
            }

        }

        if (!$found) {
            $iterator->rewind();
            while ($iterator->key() !== $startingKey) {
                $iterator->next();
            }

            return new ParserResponse($iterator, null, false);
        }

        $iterator->increaseSkipping();
        $iterator->rewind();
        while ($iterator->key() !== $iteratorSeekPoint) {
            $iterator->next();
        }

        return new ParserResponse($iterator, $result, true);
    }
}
