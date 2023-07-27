<?php

// Le fichier autoload.php sert à automatiquement inclure toutes les classes gérées par composer (donc trouvables dans le dossier vendor)
// Depuis qu'on a mis en place les namespace, on les utilise afin de faire un autoloading de nos classes.
// Cet autoloading est configuré dans le fichier composer.json.
require __DIR__. '/../vendor/autoload.php';

// On récupère la page demandée qui est dans le paramètre GET
$page = filter_input(INPUT_GET, 'page');

// Si on est sur la page d'accueil, $page est null, donc on lui donne une valeur pour indiquer qu'on est sur la page d'accueil

if($page === null) {
    $page = "/";
}

// On gère maintenant nos routes avec AltoRouter. Ce dernier est installé via Composer et chargé via l'autoloader fourni par Composer.
// On instancie donc la classe AltoRouter maintenant disponible
$router = new AltoRouter();

// Avec la méthode setBasePath, on indique à AltoRouter la partie de l'url qui sera commune à toutes les pages, ainsi il ne prendra en compte que la partie changeante.
//Pour lui préciser cette partie commune, on va la chercher dans la variable superglobale $_SERVER. Celle-ci contient plein d'infos sur la requete et notre fichier .htaccess est venu y incorporer le chemin de base de toutes nos pages.
$router->setBasePath($_SERVER['BASE_URI']);

// La méthode map permet d'ajouter une route à la liste des routes d'AltoRouter
$router->map(
    'GET', // Méthode HTTP pour accéder à la page (GET la plupart du temps)
    '/', // URL de la route
    // Cible de la route (code à exécuter si on arrive sur cette route)
    [
        'controller' => 'MainController', 
        'method' => 'prologueAction',
    ],
    'prologue' // Etiquette  unique permettant d'identifier et de générer des urls vers cette route

);

$router->map(
    'GET', 
    '/contact',
    [
        'controller' => 'MainController',
        'method' => 'contactAction'
    ],
    'page-contact'
);

$router->map(
    'GET', 
    '/test',
    [
        'controller' => 'MainController',
        'method' => 'testAction'
    ],
    'page-test'
);

$router->map(
    'GET', 
    '/mentions-legales',
    [
        'controller' => 'MainController',
        'method' => 'legalNoticeAction'
    ],
    'page-legal-notice'
);


$router->map(
    'GET',
    '/catalogue/categorie/[i:categoryId]',
    [
        'controller' => 'CatalogController',
        'method' => 'categoryAction'
    ],
    'catalog-category'
);

$router->map(
    'GET',
    '/catalogue/type/[i:typeId]',
    [
        'controller' => 'CatalogController',
        'method' => 'typeAction'
    ],
    'catalog-type'
);

$router->map(
    'GET',
    '/catalogue/marque/[i:brandId]',
    [
        'controller' => 'CatalogController',
        'method' => 'brandAction'
    ],
    'catalog-brand'
);

$router->map(
    'GET',
    '/catalogue/produit/[i:productId]',
    [
        'controller' => 'CatalogController',
        'method' => 'productAction'
    ],
    'catalog-product'
);

// La méthode match permet de vérifier si la page sur laquelle on est fait partie des routes existantes. Si c'est le cas, elle renvoie un tableau avec les infos de la route actuelle. Sinon elle renvoie false.
$match = $router->match();



// On vérifie si la route demandée par l'utilisateur existe, donc si $match est différent de false

if($match) {

    // On récupère le nom du controller et de la méthode à exécuter dans l'entrée target de $match
    $controllerName = 'App\Controllers\\' .$match['target']['controller'];
    $methodName = $match['target']['method'];
    $URLparameters = $match['params'];

} else {
    
    // Si la route n'existe pas, on définit les variables contenant le  controller et la méthode comme étant le controller des erreurs et la méthode errr404
    $controllerName = "App\Controllers\ErrorController";
    $methodName = "error404Action";
    $URLparameters = [];

}

// Une fois le controller et la méthode à exécuter définis, on peut instancier et dispatcher vers la bonne méthode
$controller = new $controllerName();
$controller->$methodName($URLparameters);
