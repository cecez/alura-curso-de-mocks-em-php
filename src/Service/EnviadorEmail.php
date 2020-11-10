<?php


namespace Alura\Leilao\Service;


use Alura\Leilao\Model\Leilao;
use DomainException;

class EnviadorEmail
{
    /**
     * @param  Leilao  $leilao
     * @throws  DomainException Quando e-mail não é enviado.
     */
    public function notificarTerminoLeilao(Leilao $leilao): void
    {

        $sucesso = mail(
            'usuario@cecez.com.br',
            'Leilão finalizado',
            "O leilão {$leilao->recuperarDescricao()} foi finalizado"
        );

        if (!$sucesso) {
            throw new DomainException('Erro ao enviar e-mail');
        }

    }
}