<?php

namespace Antbank\AccountBalanceParser\DbItaly\Parser;

use Antbank\AccountBalanceParser\DbItaly\Transaction\Transaction;

class ParserResponse
{
    /**
     * @var \Iterator
     */
    protected $iterator;

    /**
     * @var Transaction|null
     */
    protected $result;

    /**
     * @var bool
     */
    protected $stopPropagation;

    /**
     * ParserResponse constructor.
     * @param \Iterator $iterator
     * @param \Antbank\AccountBalanceParser\DbItaly\Transaction\Transaction|null $result
     * @param bool $stopPropagation
     */
    public function __construct(\Iterator $iterator, $result, $stopPropagation)
    {
        $this->iterator = $iterator;
        $this->result = $result;
        $this->stopPropagation = $stopPropagation;
    }

    /**
     * @return \Iterator
     */
    public function getIterator()
    {
        return $this->iterator;
    }

    /**
     * @return Transaction|null
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return bool
     */
    public function isStopPropagation()
    {
        return $this->stopPropagation;
    }
}
