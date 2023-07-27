<?php

namespace App\Controllers;

// Controller hérité par tous les controllers. Il contient les méthodes et propriétés communes à ceux-ci
class CoreController {


    /**
     * Méthode servant à afficher une page. On lui donne le nom du template principal et elle inclut tous les templates de la page
     *
     * @param string $viewName Nom du template principal
     * @param array $viewData Tableau contenant les infos à passer aux templates
     * @return void
     */
    function show($viewName, $viewData = [])
    {

        // On a besoin de la variable $router crée dans index.php pour générer des urls. Mais comme les fonctions sont totalement hermétiques, on n'y a pas accès.
        // Le mot-clé global permet d'indiquer à PHP d'aller chercher la variable peu importe le contexte et les frontières. En gros, de ne plus respecter les règles.
        //! C'est une très mauvaise pratique, mais pour l'instant on ne sait pas faire autrement.
        global $router;

        // Dans tous les templates, on aura besoin d'afficher du CSS et du JS. Pour afficher correctement ces derniers sur toutes les pages, on utilise une url absolue (qui démarre du nom de domaine). Dans notre cas de figure, le site est rangé dans plusieurs dossiers après le nom de domaine. Donc on récupère le chemin de ces dossiers à l'aide de la variable $_SERVER.
        // Ici on vérifie si $_SERVER contient BASE_URI, si c'est le cas on récupère cette valeur qui va servir à générer les URLS vers les assets
        $absoluteUrl = (isset($_SERVER['BASE_URI'])) ? $_SERVER['BASE_URI'] : '';

        // la fonction extract permet de "déballer" la variable $viewData. Elle va prendre chaque entrée de celle-ci, créer une variable à son nom et ranger sa valeur à l'intérieur.
        // Exemple : 
        // $exemple = [
        //   'entree1' => true,
        //   'entree2'  => "coucou"
        // ];
        // 
        // extract($exemple) va créer : 
        // Une variable $entree1 contenant true
        // Une variable $entree2 contenant "coucou"

        extract($viewData);

        require_once __DIR__ . '/../views/header.tpl.php';
        require_once __DIR__ . '/../views/' . $viewName . '.tpl.php';
        require_once __DIR__ . '/../views/footer.tpl.php';
    }

}