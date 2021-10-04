<?php
require_once('./server/request.class.php');

class Endpoint {
  public function set ($uri) {
    $this->uri = $uri;
  }

  public function json ($data) {
    header('Content-type: application/json');
    header('Access-Control-Allow-Headers: *');
    header('Access-Control-Allow-Origin: *');
    // header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
    header('Access-Control-Max-Age: 600');
    header('Access-Control-Allow-Credentials: true');
    echo json_encode($data);
  }

  /* E.g. /api/batches/:batch_number */
  public function request () {
    return new Request($this->uri);
  }
}
?>
