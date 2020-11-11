<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Leilao as LeilaoModel;
use \Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Service\Encerrador;
use Alura\Leilao\Service\EnviadorEmail;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EncerradorTest extends TestCase
{

    /** @var Encerrador */
    private $_encerrador;

    /** @var LeilaoModel */
    private $_ipad;

    /** @var LeilaoModel */
    private $_iphone;

    /** @var MockObject */
    private $_enviadorEmail;

    protected function setUp(): void
    {
        // cria models
        $this->_ipad   = new LeilaoModel('iPad 2020', new \DateTimeImmutable('8 days ago'));
        $this->_iphone = new LeilaoModel('iPhone 2020', new \DateTimeImmutable('10 days ago'));

        // cria mocks
        $mockLeilaoDao = $this->createMock(LeilaoDao::class);
        // instrui mock
        $mockLeilaoDao->method('recuperarNaoFinalizados')->willReturn([$this->_ipad, $this->_iphone]);
        // informa que espera-se duas chamadas ao método atualiza
        $mockLeilaoDao->expects($this->exactly(2))
            ->method('atualiza')
            ->withConsecutive([$this->_ipad], [$this->_iphone]);

        $this->_enviadorEmail = $this->createMock(EnviadorEmail::class);

        // chama código a se testar
        $this->_encerrador = new Encerrador($mockLeilaoDao, $this->_enviadorEmail);
    }

    public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados()
    {

        $this->_encerrador->encerra();

        // verifica se está ok
        $leiloes = [$this->_ipad, $this->_iphone];
        self::assertCount(2, $leiloes);
        self::assertTrue($leiloes[0]->estaFinalizado());
        self::assertTrue($leiloes[1]->estaFinalizado());
    }

    public function testDeveContinuarOProcessamentoAoEncontrarErroAoEnviarEmail()
    {
        $e = new \DomainException('Erro ao enviar e-mail');

        $this->_enviadorEmail->expects($this->exactly(2))
                             ->method('notificarTerminoLeilao')
                             ->willThrowException($e);

        $this->_encerrador->encerra();

    }

    public function testSoDeveEnviarEmailComLeilaoFinalizado()
    {
        $this->_enviadorEmail->expects($this->exactly(2))
                             ->method('notificarTerminoLeilao')
                             ->willReturnCallback(function (LeilaoModel $leilao) {
                                self::assertTrue($leilao->estaFinalizado());
                             });

        $this->_encerrador->encerra();
    }

}