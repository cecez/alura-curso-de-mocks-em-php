<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Dao\Leilao;
use Alura\Leilao\Model\Leilao as ModelLeilao;

class LeilaoDaoMock extends Leilao
{
    /**
     * @var array
     */
    private $_leiloes;

    /**
     * LeilaoDaoMock constructor.
     */
    public function __construct()
    {
    }


    public function salva(ModelLeilao $leilao): void
    {
        $this->_leiloes[] = $leilao;
    }

    /**
     * @return ModelLeilao[]
     */
    public function recuperarNaoFinalizados(): array
    {
        return array_filter($this->_leiloes, function(ModelLeilao $leilao) {
            return !$leilao->estaFinalizado();
        });
    }

    /**
     * @return ModelLeilao[]
     */
    public function recuperarFinalizados(): array
    {
        return array_filter($this->_leiloes, function(ModelLeilao $leilao) {
            return $leilao->estaFinalizado();
        });
    }

    public function atualiza(ModelLeilao $leilao)
    {
    }
}