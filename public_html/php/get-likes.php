<?php

$database = new SQLiteDatabase('likes.db', 0666, $error);

$pageID = $_GET['pageid'];

if(!is_numeric($pageID)) die('ID is not in correct types');

$query = "SELECT COUNT(*) as count FROM tblLikes WHERE pageID=$pageID";

$result = $database->query($query, SQLITE_ASSOC);

$rows = $result->fetch();

$row = $rows['count'];

if($row > 0 )
echo $row;