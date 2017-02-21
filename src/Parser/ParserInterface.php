<?php

namespace Antbank\AccountBalanceParser\DbItaly\Parser;

interface ParserInterface
{
    public function parse(string $input): array;
}