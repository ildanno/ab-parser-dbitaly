<?php

namespace Antbank\AccountBalanceParser\DbItaly\Parser;

use Antbank\AccountBalanceParser\DbItaly\Parser\Strategy\StrategyInterface;

class Parser implements ParserInterface
{
    /**
     * @var StrategyInterface[]
     */
    protected $strategies;

    /**
     * Parser constructor.
     * @param StrategyInterface[] $strategies
     */
    public function __construct(array $strategies)
    {
        $this->strategies = $strategies;
    }

    public function parse(string $input): array
    {
        $lines = explode(PHP_EOL, $input);

        $iterator = new \ArrayIterator(array_filter($lines));
        $iterator->rewind();

        $transactions = [];
        while ($iterator->valid()) {

            $transaction = null;
            foreach ($this->strategies as $strategy) {
                $result = $strategy->parse($iterator);
                if ($result->isStopPropagation()) {
                    $transaction = $result->getResult();
                    $transactions[] = $transaction;
                }
            }

            if ($transaction === null) {
                $iterator->next();
            }
        }

        return $transactions;
    }
}
