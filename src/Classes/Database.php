<?php

class Database {
  public function get() {
    $config = include '../../../configs/db.php';
    $db = Zend_Db::factory($config['adapter'], $config['params']);
    return $db;
  }
}