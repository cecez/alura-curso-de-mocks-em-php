<?php

namespace Alura\Leilao\Tests\Service;


use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Service\Encerrador;
use PHPUnit\Framework\TestCase;

class EncerradorTest extends TestCase
{

    public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados()
    {
        // cria models
        $ipad   = new Leilao('iPad 2020', new \DateTimeImmutable('8 days ago'));
        $iphone = new Leilao('iPhone 2020', new \DateTimeImmutable('10 days ago'));

        // salva models
        $leilaoDao = new LeilaoDao();
        $leilaoDao->salva($ipad);
        $leilaoDao->salva($iphone);

        // chama código a se testar
        $encerrador = new Encerrador();
        $encerrador->encerra();

        // verifica se está ok
        // TODO escrever códigos
//        self::assertCount(2, );
//        self::assertEquals();
    }

}