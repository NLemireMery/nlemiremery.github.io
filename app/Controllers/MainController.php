<?php

namespace App\Controllers;

class MainController extends CoreController {

    /**
     * Méthode affichant la page d'accueil
     *
     * @return void
     */
    public function prologueAction()
    {
        $this->show('prologue');
    }


}