<?php

namespace AntbankTest\AccountBalanceParser\DbItaly\Unit\Parser\Strategy;

use Antbank\AccountBalanceParser\DbItaly\Parser\Strategy\MovimentoAnnualeWithValutaStrategy;
use Antbank\AccountBalanceParser\DbItaly\Transaction\DeutscheBankCreditCardTransaction;
use Antbank\AccountBalanceReader\Parser\ParserResponse;
use PHPUnit\Framework\TestCase;

class MovimentoAnnualeWithValutaStrategyTest extends TestCase
{
    public function testParse()
    {
        $input = [
            '12039812391280391283092 10293829 04/11/2016 05/11/2016 A Very Foreign Company IMPORTO IN SWISS FRANC 90,40 CHF',
            'Cambio 1,054842. Commissione per tasso di cambio 1,47 85,70',
            'EOF',
        ];
        $iterator = new \ArrayIterator($input);

        $strategy = new MovimentoAnnualeWithValutaStrategy();
        $response = $strategy->parse($iterator);

        self::assertInstanceOf(ParserResponse::class, $response);
        self::assertTrue($response->isStopPropagation());
        self::assertEquals('EOF', $response->getIterator()->current());

        self::assertInstanceOf(DeutscheBankCreditCardTransaction::class, $response->getResult());
        $transaction = $response->getResult();
        self::assertEquals('12039812391280391283092 10293829', $transaction->getCodiceRiferimento());
        self::assertEquals('04/11/2016', $transaction->getDataAcquisto()->format('d/m/Y'));
        self::assertEquals('05/11/2016', $transaction->getDataRegistrazione()->format('d/m/Y'));
        self::assertEquals('A Very Foreign Company IMPORTO IN SWISS FRANC', $transaction->getDescrizioneOperazioni());
        self::assertEquals('CHF', $transaction->getValuta());
        self::assertEquals(90.4, $transaction->getImportoValuta());
        self::assertEquals(1.054842, $transaction->getTassoCambio());
        self::assertEquals(1.47, $transaction->getCommissioniCambio());
        self::assertEquals(85.7, $transaction->getAcquisti());
    }

    public function testParseFailSecondLine()
    {
        $line1 = '12039812391280391283092 10293829 04/11/2016 05/11/2016 A Very Foreign Company IMPORTO IN SWISS FRANC 90,40 CHF';
        $line2 = 'Text somehow failing';

        $input = [
            $line1,
            $line2,
            'EOF',
        ];
        $iterator = new \ArrayIterator($input);

        $strategy = new MovimentoAnnualeWithValutaStrategy();
        $response = $strategy->parse($iterator);

        self::assertInstanceOf(ParserResponse::class, $response);
        self::assertFalse($response->isStopPropagation());
        self::assertEquals($line1, $response->getIterator()->current());

        self::assertEquals(null, $response->getResult());
    }
}
