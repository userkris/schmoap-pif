<?php
require_once('./server/router.class.php');
require_once('./server/endpoint.class.php');

class App {
  public function __construct () {
    $Router = new Router();
    $Endpoint = new Endpoint();

    $Endpoint->set($Router->getUri());

    $result = $Router->redirect($Endpoint->request());

    if ($result) {
      $Endpoint->json($result);
    }
  }
}
?>
