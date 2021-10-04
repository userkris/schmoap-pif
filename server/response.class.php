<?php
class Response {
  public function __construct ($data = []) {
    $snackbar = false;
    $real_data = false;

    if (array_key_exists('state', $data)) {
      if (!array_key_exists('success', $data)) {
        $data['success'] = null;
      }

      if (!array_key_exists('error', $data)) {
        $data['error'] = null;
      }

      if (!array_key_exists('empty', $data)) {
        $data['empty'] = null;
      }

      /* Snackbar */
      if ($data['state']) {
        $snackbar = $data['success'];
      }

      /* Data */
      if (array_key_exists('data', $data)) {
        if (!empty($data['data'])) {
          $real_data = $data['data'];
        }
        if (empty($real_data) && $data['state']) {
          $snackbar = $data['empty'];
        }
      }

      if (!$data['state']) {
        $snackbar = $data['error'];
      }
    }

    $this->response = [
      'snackbar' => $snackbar,
      'data' =>  $real_data
    ];
  }

  public function build () {
    return $this->response;
  }
}
?>
