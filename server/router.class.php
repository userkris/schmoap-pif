<?php
require_once('./server/db.class.php');
require_once('./server/ingredient.class.php');

class Router {
  public function __construct () {
    $this->uri = rtrim($_SERVER['REQUEST_URI'], '/');
    $this->DB = new DB();
    $this->Ingredient = new Ingredient();
  }

  private function loadFrontend () {
    require_once('./pif-client/dist/index.html');
  }

  private function json () {
    header('Content-type: application/json');
    // header('Access-Control-Allow-Headers: *');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
    header('Access-Control-Max-Age: 600');
    header('Access-Control-Allow-Credentials: true');

    $json = file_get_contents('php://input');
    return (array) json_decode($json);
  }

  public function getUri () {
    return $this->uri;
  }

  public function redirect ($request) {
    $ep = $request->endpoint;

    if ($request->api) {

      if ($ep == 'batches') {
        return [
          'batches' => $this->DB->get_all()
        ];
      }

      if ($ep == 'batch') {
        return [
          'batch' => $this->DB->get_item([ 'id' => $request->value ])
        ];
      }

      /* Ingredients */
      /* Select All */
      if ($ep == 'ingredients') {
        return $this->Ingredient->selectAll();
      }

      /* Select by id */
      if ($ep == 'ingredient') {
        if ($request->value) {
          return $this->Ingredient->select([ 'id' => $request->value ]);
        }
      }

      /* Insert */
      if ($ep == 'ingredient-insert') {
        return $this->Ingredient->insert($this->json());
      }

      /* Delete */
      if ($ep == 'ingredient-delete') {
        return $this->Ingredient->delete($this->json());
      }

      /* Update */
      if ($ep == 'ingredient-update') {
        return $this->Ingredient->update($this->json());
      }


      /* Test Post */
      if ($ep == 'test') {
        return $this->json();
      }

    }
    $this->loadFrontend();
    return false;
  }
}
?>
