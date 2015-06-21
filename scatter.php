<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// read configuration file
$config = parse_ini_file("config.ini");

// initialize the global database object
try {
  $database = new PDO("sqlite:" . $config["database"]);
}
catch(PDOException $e) {
  echo $e->getMessage();
}

function getScatterData() {
  global $database;

  $sql = $database->prepare("SELECT tag, book_page AS page, book_id, type FROM tags");
  if ($sql->execute())
    return $sql->fetchAll();
}

$scatter = getScatterData();
// process data
foreach ($scatter as $id => $tag) {
  if ($tag["type"] == "item")
    $scatter[$id]["chapter"] = 0;
  else
    $scatter[$id]["chapter"] = explode(".", $tag["book_id"])[0];

  // less data footprint
  unset($scatter[$id]["0"]);
  unset($scatter[$id]["1"]);
  unset($scatter[$id]["2"]);
  unset($scatter[$id]["3"]);
  unset($scatter[$id]["book_id"]);
  unset($scatter[$id]["type"]);
}
print json_encode($scatter, JSON_PRETTY_PRINT);

?>

