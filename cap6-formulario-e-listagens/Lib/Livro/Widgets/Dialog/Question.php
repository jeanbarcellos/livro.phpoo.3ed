<?php

namespace Livro\Widgets\Dialog;

use Livro\Control\Action;
use Livro\Widgets\Base\Element;

/**
 * Exibe perguntas ao usuário
 *
 * @author Pablo Dall'Oglio
 */
class Question {

    /**
     * Instancia o questionamento
     *
     * @param $message Pergunta ao usuário
     * @param $action_yes Cção para resposta positiva
     * @param $action_no Ação para resposta negativa
     */
    function __construct($message, Action $action_yes, Action $action_no = NULL) {
        $div = new Element('div');
        $div->class = 'alert alert-warning';

        // converte os nomes de métodos em URL's
        $url_yes = $action_yes->serialize();

        $link_yes = new Element('a');
        $link_yes->href = $url_yes;
        $link_yes->class = 'btn btn-success';
        $link_yes->add('Sim');

        $message .= '&nbsp;' . $link_yes;
        if ($action_no) {
            $url_no = $action_no->serialize();

            $link_no = new Element('a');
            $link_no->href = $url_no;
            $link_no->class = 'btn btn-default';
            $link_no->add('Não');

            $message .= $link_no;
        }

        $div->add($message);
        $div->show();
    }

}
