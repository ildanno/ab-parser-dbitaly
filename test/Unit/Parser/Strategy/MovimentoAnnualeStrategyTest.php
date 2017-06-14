<?php

namespace AntbankTest\AccountBalanceParser\DbItaly\Unit\Parser\Strategy;

use Antbank\AccountBalanceParser\DbItaly\Parser\Strategy\MovimentoAnnualeStrategy;
use Antbank\AccountBalanceParser\DbItaly\Transaction\DeutscheBankCreditCardTransaction;
use PHPUnit\Framework\TestCase;

class MovimentoAnnualeStrategyTest extends TestCase
{
    public function testParseMovimentoDare()
    {
        $input = [
            '10293810298310293810292 01901912 16/11/2016 18/11/2016 STORE 123 MILANO 6,99',
            'EOF',
        ];
        $iterator = new \ArrayIterator($input);
        $iterator->rewind();

        $strategy = new MovimentoAnnualeStrategy();
        $response = $strategy->parse($iterator);

        self::assertTrue($response->isStopPropagation());
        self::assertInstanceOf(DeutscheBankCreditCardTransaction::class, $response->getResult());

        $transaction = $response->getResult();

        self::assertEquals('10293810298310293810292 01901912', $transaction->getCodiceRiferimento());
        self::assertEquals('16/11/2016', $transaction->getDataAcquisto()->format('d/m/Y'));
        self::assertEquals('18/11/2016', $transaction->getDataRegistrazione()->format('d/m/Y'));
        self::assertEquals('STORE 123 MILANO', $transaction->getDescrizioneOperazioni());
        self::assertEquals(6.99, $transaction->getAcquisti());

        self::assertEquals('EOF', $response->getIterator()->current());
    }

    public function testParseMovimentoAvere()
    {
        $input = [
            '00201611151906395849302 15/11/2016 16/11/2016 PAGAMENTO CON ADDEBITO SU VS C/C 1.234,56-',
            'EOF',
        ];
        $iterator = new \ArrayIterator($input);
        $iterator->rewind();

        $strategy = new MovimentoAnnualeStrategy();
        $response = $strategy->parse($iterator);

        self::assertTrue($response->isStopPropagation());
        self::assertInstanceOf(DeutscheBankCreditCardTransaction::class, $response->getResult());

        $transaction = $response->getResult();

        self::assertEquals('00201611151906395849302', $transaction->getCodiceRiferimento());
        self::assertEquals('15/11/2016', $transaction->getDataAcquisto()->format('d/m/Y'));
        self::assertEquals('16/11/2016', $transaction->getDataRegistrazione()->format('d/m/Y'));
        self::assertEquals('PAGAMENTO CON ADDEBITO SU VS C/C', $transaction->getDescrizioneOperazioni());
        self::assertEquals(1234.56, $transaction->getPagamenti());

        self::assertEquals('EOF', $response->getIterator()->current());
    }

    /**
     * @dataProvider notMatchingMovimentoAvereDataProvider
     * @param string $inputLine
     */
    public function testParseMovimentoAvereNotMatching(string $inputLine)
    {
        $input = [
            $inputLine,
            'Second line input',
        ];

        $iterator = new \ArrayIterator($input);
        $iterator->rewind();

        $strategy = new MovimentoAnnualeStrategy();
        $response = $strategy->parse($iterator);

        self::assertFalse($response->isStopPropagation());
        self::assertNull($response->getResult());

        self::assertTrue($response->getIterator()->valid());
        self::assertEquals($inputLine, $response->getIterator()->current());
    }

    public function notMatchingMovimentoAvereDataProvider()
    {
        return [
            [
                'Cambio 1,061836. Commissione per tasso di cambio 0,83 48,03',
            ],
            [
                'Carta N. 5425 12** **** *937',
            ],
            [
                '239482394029384020 15/11/2016 16/11/2016 PAGAMENTO CON ADDEBITO SU VS C/C 1.234,56- EUR',
            ],
            [
                'TRANSACTION 02394823098209842039482 15/11/2016 16/11/2016 PAGAMENTO CON ADDEBITO SU VS C/C 1.234,56-',
            ],
            [
                '23482398423794827394823 02394802 06/12/2016 07/12/2016 BIG COMPANY *Very Big Company 123-456-7890 IMPORTO IN U.S. DOLLAR 1,99 USD',
            ],
        ];
    }
}
