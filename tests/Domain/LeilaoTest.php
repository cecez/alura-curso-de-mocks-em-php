<?php

namespace Alura\Leilao\Tests\Domain;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use DomainException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class LeilaoTest
 *
 * @package Alura\Leilao\Tests\Domain
 *
 * TODO usar mocks nesta classe de teste
 */
class LeilaoTest extends TestCase
{
    /**
     * @var Usuario|MockObject
     */
    private $_mockUsuario1;
    /**
     * @var Usuario|MockObject
     */
    private $_mockUsuario2;

    public function setUp(): void
    {
        $this->_criaMocks();
    }


    public function testProporLanceEmLeilaoFinalizadoDeveLancarExcecao()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Este leilão já está finalizado');

        $leilao = new Leilao('Fiat 147 0KM');
        $leilao->finaliza();

        $leilao->recebeLance(new Lance($this->_mockUsuario1, 1000));
    }

    /**
     * @param int $qtdEsperado
     * @param Lance[] $lances
     * @dataProvider dadosParaProporLances
     */
    public function testProporLancesEmLeilaoDeveFuncionar(int $qtdEsperado, array $lances)
    {
        $leilao = new Leilao('Fiat 147 0KM');
        foreach ($lances as $lance) {
            $leilao->recebeLance($lance);
        }

        static::assertCount($qtdEsperado, $leilao->getLances());
    }

    public function testMesmoUsuarioNaoPodeProporDoisLancesSeguidos()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Usuário já deu o último lance');

        $leilao = new Leilao('Objeto inútil');

        $leilao->recebeLance(new Lance($this->_mockUsuario1, 1000));
        $leilao->recebeLance(new Lance($this->_mockUsuario1, 1100));
    }

    public function dadosParaProporLances()
    {
        $this->_criaMocks();

        return [
            [1, [new Lance($this->_mockUsuario1, 1000)]],
            [2, [new Lance($this->_mockUsuario1, 1000), new Lance($this->_mockUsuario2, 2000)]],
        ];
    }

    private function _criaMocks(): void
    {
        // criando mocks para usuários
        $this->_mockUsuario1 = $this->getMockBuilder(Usuario::class)
            ->setConstructorArgs(['Usuário 1'])->getMock();
        $this->_mockUsuario2 = $this->getMockBuilder(Usuario::class)
            ->setConstructorArgs(['Usuário 2'])->getMock();
    }
}
