<?php


namespace Alura\Leilao\Service;


use Alura\Leilao\Model\Leilao;

class EnviadorEmail
{
    public function notificarTerminoLeilao(Leilao $leilao): void
    {

        $sucesso = mail(
            'usuario@cecez.com.br',
            'Leilão finalizado',
            "O leilão {$leilao->recuperarDescricao()} foi finalizado"
        );

        if (!$sucesso) {
            throw new \DomainException('Erro ao enviar e-mail');
        }

    }
}