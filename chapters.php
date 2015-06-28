<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// read configuration file
$config = parse_ini_file("../stacks-website/config.ini");

// initialize the global database object
try {
  $database = new PDO("sqlite:../stacks-website/" . $config["database"]);
}
catch(PDOException $e) {
  echo $e->getMessage();
}

function getChapterData() {
  global $database;

  $sql = $database->prepare("SELECT number, title, filename FROM sections WHERE number NOT LIKE '%.%' ORDER BY CAST(number AS INTEGER)");
  if ($sql->execute())
    return $sql->fetchAll();
}

$chapters = getChapterData();
foreach ($chapters as $id => $chapter) {
  for ($i = 0; $i < 3; $i++)
    unset($chapters[$id][$i]);
  unset($chapters[$id]["filename"]);
}
print json_encode($chapters);

?>

