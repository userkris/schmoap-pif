<?php
class Request {
  public function __construct ($uri) {
    $uri = explode('/', $uri);

    $this->api = array_key_exists(1, $uri) && $uri[1] === 'api' ? true : false;
    $this->endpoint = array_key_exists(2, $uri) ? $uri[2] : false;
    $this->value = array_key_exists(3, $uri) ? $uri[3] : false;
  }
}
?>
