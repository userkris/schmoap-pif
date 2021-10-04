<?php
class DB {
  /* CONFIG */
  public function __construct () {
    $this->host = CONFIG['db']['host'];
    $this->dbname = CONFIG['db']['name'];
    $this->user = CONFIG['db']['user'];
    $this->pass = CONFIG['db']['pass'];
  }

  /* CONNECT */
  public function connect () {
    try {
      $pdo = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname.';charset=utf8', $this->user, $this->pass);
      return $pdo;
    } catch (PDOException $e) {
        echo "Error!: " . $e->getMessage() . "<br/>";
    }
  }

  /* INSERT */
  public function insert ($data = array()) {
    $data['time_created'] = date('Y-m-d H:i:s');
    $data['time_last_modified'] = $data['time_created'];

    $link = $this->connect();
    $stmt = $link->prepare('INSERT INTO `sch_pif` (`time_created`, `time_last_modified`, `data_json`, `soap_created`) VALUES (:time_created, :time_last_modified, :data_json, :soap_created)');
    $stmt->execute([
      'time_created' => $data['time_created'],
      'time_last_modified' => $data['time_created'],
      'data_json' => $data['json'],
      'soap_created' => $data['soap_created']
    ]);
    $stmt = null;
    $link = null;
  }

  /* UPDATE */
  public function update ($data = array()) {
    $data['time_last_modified'] = date('Y-m-d H:i:s');

    $link = $this->connect();
    $stmt = $link->prepare('UPDATE `sch_pif` SET `time_last_modified` = :time_last_modified, `data_json` = :data_json, `soap_created` = :soap_created WHERE `id` = :id');
    $stmt->execute([
      'id' => $data['id'],
      'time_last_modified' => $data['time_last_modified'],
      'data_json' => $data['json'],
      'soap_created' => $data['soap_created']
    ]);
    $stmt = null;
    $link = null;
  }

  /* DELETE */
  public function delete ($data = array()) {
    $link = $this->connect();
    $stmt = $link->prepare('DELETE FROM `sch_pif` WHERE `id` = :id');
    $stmt->execute([
      'id' => $data['id']
    ]);
    $stmt = null;
    $link = null;
  }

  /* SELECT */
  public function get_all () {
    $link = $this->connect();
    return $link->query('SELECT * FROM `sch_pif` ORDER BY DATE(soap_created) DESC')->fetchAll(PDO::FETCH_ASSOC);
  }

  /* SELECT BY ID */
  public function get_item ($data = array()) {
    $link = $this->connect();
    $stmt = $link->prepare('SELECT * FROM `sch_pif` WHERE `id` = :id');
    $stmt->execute([
      'id' => $data['id']
    ]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = null;
    $link = null;
    return $item;
  }

  /* SEARCH */
  public function search_items ($data = array()) {
    $link = $this->connect();
    $stmt = $link->prepare('SELECT * FROM `sch_pif` WHERE LOWER(`data_json`) LIKE LOWER(:request)');
    $stmt->execute([
      'request' => '%'.$data['request'].'%'
    ]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = null;
    $link = null;
    return $items;
  }

  /* DUPLICATE BY ID */
  public function duplicate_item ($data = array()) {
    $item = $this->get_item($data);
    $json = json_decode($item['data_json']);
    $json->pifdata->name .= ' (<b>COPY</b>)';
    $this->insert([
      'soap_created' => $json->pifdata->creationDate,
      'json' => json_encode($json)
    ]);
    return false;
  }

  protected function fetch ($stmt, $single = false) {
    if (!is_object($stmt)) {
      return $stmt;
    }
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($single) {
      return array_key_exists(0, $result) ? $result[0] : false;
    }
    return $result;
  }
}
?>
