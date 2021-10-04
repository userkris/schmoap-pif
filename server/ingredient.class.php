<?php
require_once('./server/response.class.php');

class Ingredient extends DB {

  /* INSERT */
  public function insert ($data = []) {
    $link = $this->connect();
    $stmt = $link->prepare('INSERT INTO `sch_app_ingredients` (`name`, `inci`, `supplier`, `supplier_url`, `category`) VALUES (:name, :inci, :supplier, :supplier_url, :category)');

    $response = new Response([
      'state' => $stmt->execute($data),
      'success' => $data['name'] . ' has been added.',
      'error' => 'Error adding ' . $data['name'] . '.'
    ]);
    return $response->build();

    /*
    $data = [
      'name' => 'Olive Pomace Oil',
      'inci' => 'Olea europaea (Olive) Fruit Oil',
      'supplier' => 'KTC',
      'supplier_url' => 'https://google.com',
      'category' => 'Oil'
    ]
    */
  }

  /* UPDATE */
  public function update ($data = []) {
    $link = $this->connect();
    $stmt = $link->prepare('UPDATE `sch_app_ingredients` SET `name` = :name, `inci` = :inci, `supplier` = :supplier, `supplier_url` = :supplier_url, `category` = :category WHERE `id` = :id');

    $response = new Response([
      'state' => $stmt->execute($data),
      'success' => $data['name'] . ' has been updated.',
      'error' => 'Error updating ' . $data['name'] . '.'
    ]);

    return $response->build();
  }

  /* DELETE */
  public function delete ($data = []) {
    $link = $this->connect();
    $stmt = $link->prepare('DELETE FROM `sch_app_ingredients` WHERE `id` = :id');

    $response = new Response([
      'state' => $stmt->execute($data),
      'success' => 'Ingredient has been removed.',
      'error' => 'Cannot remove the ingredient.'
    ]);

    return $response->build();
  }

  /* SELECT */
  public function select ($data = []) {
    $link = $this->connect();
    $stmt = $link->prepare('SELECT * FROM `sch_app_ingredients` WHERE `id` = :id LIMIT 1');

    $response = new Response([
      'state' => $stmt->execute($data),
      'error' => 'Failed to get ingredient.',
      'empty' => 'Ingredient has not been found.',
      'data' => $this->fetch($stmt, 'single')
    ]);

    return $response->build();
  }

  /* SELECT ALL */
  public function selectAll () {
    $link = $this->connect();
    $stmt = $link->query('SELECT * FROM `sch_app_ingredients` ORDER BY `name` ASC');
    $response = new Response([
      'state' => $stmt,
      'error' => 'Failed to get list of ingredients.',
      'data' => $this->fetch($stmt)
    ]);

    return $response->build();
  }

}
?>
