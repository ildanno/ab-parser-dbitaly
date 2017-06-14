<?php

namespace Antbank\AccountBalanceParser\DbItaly\Parser\Strategy;

use Antbank\AccountBalanceReader\Parser\ParserResponse;

interface StrategyInterface
{
    const PATTERN_CODE = '\d{23}( \d{8})?';
    const PATTERN_DATE = '\d{2}\/\d{2}\/\d{4}';
    const PATTERN_AMOUNT = '[\d\.]+,\d{2,6}';
    const PATTERN_CURRENCY = '\w{3}';

    public function parse(\Iterator $iterator): ParserResponse;
}
