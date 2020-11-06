<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Leilao as LeilaoModel;
use \Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Service\Encerrador;
use PHPUnit\Framework\TestCase;

class EncerradorTest extends TestCase
{

    public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados()
    {
        // cria models
        $ipad   = new LeilaoModel('iPad 2020', new \DateTimeImmutable('8 days ago'));
        $iphone = new LeilaoModel('iPhone 2020', new \DateTimeImmutable('10 days ago'));

        // cria e configura mock
        $leilaoDao = $this->createMock(LeilaoDao::class);
        $leilaoDao->method('recuperarNaoFinalizados')->willReturn([$ipad, $iphone]);

        // chama código a se testar
        $encerrador = new Encerrador($leilaoDao);
        $encerrador->encerra();

        // verifica se está ok
        $leiloes = [$ipad, $iphone];
        self::assertCount(2, $leiloes);
        self::assertTrue($leiloes[0]->estaFinalizado());
        self::assertTrue($leiloes[1]->estaFinalizado());
    }

}