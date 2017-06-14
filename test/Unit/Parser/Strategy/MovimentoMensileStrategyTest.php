<?php

namespace AntbankTest\AccountBalanceParser\DbItaly\Unit\Parser\Strategy;

use Antbank\AccountBalanceParser\DbItaly\Parser\Strategy\MovimentoMensileStrategy;
use Antbank\AccountBalanceReader\Parser\ParserResponse;
use Antbank\AccountBalanceParser\DbItaly\Transaction\DeutscheBankCreditCardTransaction;
use PHPUnit\Framework\TestCase;

class MovimentoMensileStrategyTest extends TestCase
{
    public function testParseSingleInput()
    {
        $data = [
            '21938712893719283718923 10/12/2016',
            '10298310',
            '12/12/2016 GRANDE SUPERMERCATO',
            '40,20',
        ];
        $iterator = new \ArrayIterator($data);

        $strategy = new MovimentoMensileStrategy();
        $response = $strategy->parse($iterator);

        self::assertInstanceOf(ParserResponse::class, $response);
        self::assertInstanceOf(DeutscheBankCreditCardTransaction::class, $response->getResult());
        self::assertTrue($response->isStopPropagation());
        self::assertFalse($iterator->valid());

        $transaction = $response->getResult();
        self::assertEquals('21938712893719283718923 10298310', $transaction->getCodiceRiferimento());
        self::assertEquals('10/12/2016', $transaction->getDataAcquisto()->format('d/m/Y'));
        self::assertEquals('12/12/2016', $transaction->getDataRegistrazione()->format('d/m/Y'));
        self::assertEquals('GRANDE SUPERMERCATO', $transaction->getDescrizioneOperazioni());
        self::assertEquals(40.20, $transaction->getAcquisti());
        self::assertEquals(40.20, $transaction->getImporto());
    }

    public function testParseDoubleInput()
    {
        $data = [
            '21938712893719283718923 10/12/2016',
            '10298310',
            '12093810298310298310291 14/12/2016',
            '23910232',
            '12/12/2016 GRANDE SUPERMERCATO',
            '40,20',
            '17/12/2016 PICCOLO NEGOZIO',
            '12,99',
        ];
        $iterator = new \ArrayIterator($data);

        $strategy = new MovimentoMensileStrategy();
        $response = $strategy->parse($iterator);

        self::assertInstanceOf(ParserResponse::class, $response);
        self::assertInstanceOf(DeutscheBankCreditCardTransaction::class, $response->getResult());
        self::assertTrue($response->isStopPropagation());
        self::assertTrue($iterator->valid());

        $transaction = $response->getResult();
        self::assertEquals('21938712893719283718923 10298310', $transaction->getCodiceRiferimento());
        self::assertEquals('10/12/2016', $transaction->getDataAcquisto()->format('d/m/Y'));
        self::assertEquals('12/12/2016', $transaction->getDataRegistrazione()->format('d/m/Y'));
        self::assertEquals('GRANDE SUPERMERCATO', $transaction->getDescrizioneOperazioni());
        self::assertEquals(40.20, $transaction->getAcquisti());
        self::assertEquals(40.20, $transaction->getImporto());

        $iterator = $response->getIterator();
        self::assertEquals('12093810298310298310291 14/12/2016', $iterator->current());

        $response = $strategy->parse($iterator);
        $transaction = $response->getResult();
        self::assertEquals('12093810298310298310291 23910232', $transaction->getCodiceRiferimento());
        self::assertEquals('14/12/2016', $transaction->getDataAcquisto()->format('d/m/Y'));
        self::assertEquals('17/12/2016', $transaction->getDataRegistrazione()->format('d/m/Y'));
        self::assertEquals('PICCOLO NEGOZIO', $transaction->getDescrizioneOperazioni());
        self::assertEquals(12.99, $transaction->getAcquisti());
        self::assertEquals(12.99, $transaction->getImporto());
    }

    public function testParseMultipleInput()
    {
        $data = [
            '21938712893719283718923 10/12/2016',
            '10298310',
            '12093810298310298310291 14/12/2016',
            '23910232',
            '92834892394827394829384 16/12/2016',
            '83383838',
            '12/12/2016 GRANDE SUPERMERCATO',
            '40,20',
            '17/12/2016 PICCOLO NEGOZIO',
            '12,99',
            '19/12/2016 STORE ONLINE',
            '112,44',
        ];
        $iterator = new \ArrayIterator($data);

        $strategy = new MovimentoMensileStrategy();
        $response = $strategy->parse($iterator);

        self::assertInstanceOf(ParserResponse::class, $response);
        self::assertInstanceOf(DeutscheBankCreditCardTransaction::class, $response->getResult());
        self::assertTrue($response->isStopPropagation());
        self::assertTrue($iterator->valid());

        $transaction = $response->getResult();
        self::assertEquals('21938712893719283718923 10298310', $transaction->getCodiceRiferimento());
        self::assertEquals('10/12/2016', $transaction->getDataAcquisto()->format('d/m/Y'));
        self::assertEquals('12/12/2016', $transaction->getDataRegistrazione()->format('d/m/Y'));
        self::assertEquals('GRANDE SUPERMERCATO', $transaction->getDescrizioneOperazioni());
        self::assertEquals(40.20, $transaction->getAcquisti());
        self::assertEquals(40.20, $transaction->getImporto());

        $iterator = $response->getIterator();
        self::assertEquals('12093810298310298310291 14/12/2016', $iterator->current());

        $response = $strategy->parse($iterator);
        $transaction = $response->getResult();
        self::assertEquals('12093810298310298310291 23910232', $transaction->getCodiceRiferimento());
        self::assertEquals('14/12/2016', $transaction->getDataAcquisto()->format('d/m/Y'));
        self::assertEquals('17/12/2016', $transaction->getDataRegistrazione()->format('d/m/Y'));
        self::assertEquals('PICCOLO NEGOZIO', $transaction->getDescrizioneOperazioni());
        self::assertEquals(12.99, $transaction->getAcquisti());
        self::assertEquals(12.99, $transaction->getImporto());
    }

    public function testParseDirtyInput()
    {
        self::assertTrue(true);
        $data = [
            'Codice Titolare 1234567898',
            '',
            'DATA ACQUISTO DATA REGISTR. DESCRIZIONE DELLE OPERAZIONI',
            '',
            'Carta N. 5425 43** **** *123',
            '56789876789876678987678 10/12/2016',
            '23232232',
            '09090909090990809809808 12/12/2016',
            '65657656',
            '21342132143214321321432 12/12/2016',
            '09809809',
            '54355435435435435435434 12/12/2016',
            '10921029',
            'IMPORTO IN EURO',
            '',
            '12/12/2016 SUPERMERCATO',
            '',
            '40,20',
            '',
            '13/12/2016 AUTOGRILL',
            '',
            '13,80',
            '',
            '13/12/2016 BAR',
            '',
            '7,60',
            '',
            '13/12/2016 CENTRO COMMERCIALE',
            '',
            '36,25',
            '',
            'Totale lista movimenti',
            '',
            '1.234,56',
        ];
        $iterator = new \ArrayIterator($data);

        $strategy = new MovimentoMensileStrategy();

        for ($i = 5; $i; $i--) {
            $response = $strategy->parse($iterator);
            $iterator = $response->getIterator();
            self::assertFalse($response->isStopPropagation());
            self::assertTrue($iterator->valid());

            $iterator->next();
        }

        self::assertEquals('56789876789876678987678 10/12/2016', $iterator->current());

        $response = $strategy->parse($iterator);
        $transaction = $response->getResult();
        $iterator = $response->getIterator();
        self::assertEquals('56789876789876678987678 23232232', $transaction->getCodiceRiferimento());
        self::assertEquals('10/12/2016', $transaction->getDataAcquisto()->format('d/m/Y'));
        self::assertEquals('12/12/2016', $transaction->getDataRegistrazione()->format('d/m/Y'));
        self::assertEquals('SUPERMERCATO', $transaction->getDescrizioneOperazioni());
        self::assertEquals(40.20, $transaction->getAcquisti());
        self::assertEquals(40.20, $transaction->getImporto());

        $response = $strategy->parse($iterator);
        $transaction = $response->getResult();
        $iterator = $response->getIterator();
        self::assertEquals('09090909090990809809808 65657656', $transaction->getCodiceRiferimento());
        self::assertEquals('12/12/2016', $transaction->getDataAcquisto()->format('d/m/Y'));
        self::assertEquals('13/12/2016', $transaction->getDataRegistrazione()->format('d/m/Y'));
        self::assertEquals('AUTOGRILL', $transaction->getDescrizioneOperazioni());
        self::assertEquals(13.80, $transaction->getAcquisti());
        self::assertEquals(13.80, $transaction->getImporto());

        $response = $strategy->parse($iterator);
        $transaction = $response->getResult();
        $iterator = $response->getIterator();
        self::assertEquals('21342132143214321321432 09809809', $transaction->getCodiceRiferimento());
        self::assertEquals('12/12/2016', $transaction->getDataAcquisto()->format('d/m/Y'));
        self::assertEquals('13/12/2016', $transaction->getDataRegistrazione()->format('d/m/Y'));
        self::assertEquals('BAR', $transaction->getDescrizioneOperazioni());
        self::assertEquals(7.60, $transaction->getAcquisti());
        self::assertEquals(7.60, $transaction->getImporto());

        $response = $strategy->parse($iterator);
        $transaction = $response->getResult();
        $iterator = $response->getIterator();
        self::assertEquals('54355435435435435435434 10921029', $transaction->getCodiceRiferimento());
        self::assertEquals('12/12/2016', $transaction->getDataAcquisto()->format('d/m/Y'));
        self::assertEquals('13/12/2016', $transaction->getDataRegistrazione()->format('d/m/Y'));
        self::assertEquals('CENTRO COMMERCIALE', $transaction->getDescrizioneOperazioni());
        self::assertEquals(36.25, $transaction->getAcquisti());
        self::assertEquals(36.25, $transaction->getImporto());

        self::assertTrue($iterator->valid());
        self::assertEquals('IMPORTO IN EURO', $iterator->current());
    }
}
