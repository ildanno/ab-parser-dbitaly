<?php

namespace BoomBruno\DbMasterCardPdfReaderTest\Unit\Parser;

use Antbank\AccountBalanceParser\DbItaly\Parser\Parser;
use Antbank\AccountBalanceParser\DbItaly\Parser\Strategy\MovimentoAnnualeStrategy;
use Antbank\AccountBalanceParser\DbItaly\Parser\Strategy\MovimentoAnnualeWithValutaStrategy;
use Antbank\AccountBalanceParser\DbItaly\Parser\Strategy\MovimentoMensileStrategy;
use Antbank\AccountBalanceParser\DbItaly\Transaction\Transaction;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testParse()
    {
        $data = file_get_contents(__DIR__ . '/../../data/mc-example.txt');
        $parser = new Parser([
            new MovimentoMensileStrategy(),
            new MovimentoAnnualeStrategy(),
            new MovimentoAnnualeWithValutaStrategy(),
        ]);

        /** @var Transaction[] $output */
        $output = $parser->parse($data);
        self::assertInternalType('array', $output);

        foreach ($output as $transaction) {
            self::assertInstanceOf(Transaction::class, $transaction);
        }

        $transaction3 = $output[2]; // 85592616325078742710785 56991121 03/02/2017 04/02/2017 BOLLETTA TELEFONO 44,19
        self::assertEquals('85592616325078742710785 56991121', $transaction3->getCodiceRiferimento());
        self::assertEquals('03/02/2017', $transaction3->getDataAcquisto()->format('d/m/Y'));
        self::assertEquals('04/02/2017', $transaction3->getDataRegistrazione()->format('d/m/Y'));
        self::assertEquals('BOLLETTA TELEFONO', $transaction3->getDescrizioneOperazioni());
        self::assertEquals(44.19, $transaction3->getAcquisti());
    }
}
