<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Service\Encerrador;
use PHPUnit\Framework\TestCase;

include('LeilaoDaoMock.php');

class EncerradorTest extends TestCase
{

    public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados()
    {
        // cria models
        $ipad   = new Leilao('iPad 2020', new \DateTimeImmutable('8 days ago'));
        $iphone = new Leilao('iPhone 2020', new \DateTimeImmutable('10 days ago'));

        // salva models
        $leilaoDao = new LeilaoDaoMock();
        $leilaoDao->salva($ipad);
        $leilaoDao->salva($iphone);

        // chama código a se testar
        $encerrador = new Encerrador($leilaoDao);
        $encerrador->encerra();

        // verifica se está ok
        $leiloes = $leilaoDao->recuperarFinalizados();
        self::assertCount(2, $leiloes);
        self::assertEquals('iPad 2020', $leiloes[0]->recuperarDescricao());
        self::assertEquals('iPhone 2020', $leiloes[1]->recuperarDescricao());
    }

}