<?php

if(!file_exists('./likes.db')) die('Likes db could not exists. please run createdb.php before use this script');


$database = new SQLiteDatabase('likes.db', 0666, $error);

$unique = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);

$pageID = $_GET['pageid'];

if(!is_numeric($pageID)) die('ID is not in correct types');

$query = "SELECT COUNT(*) as count FROM tblLikes WHERE pageID=$pageID AND checkMD5='$unique'";

$result = $database->query($query, SQLITE_ASSOC);

$rows = $result->fetch();

$row = $rows['count'];

if($row > 0 )
die('You already have liked this page');


$query = "INSERT INTO tblLikes(pageID , checkMD5) VALUES ('$pageID' , '$unique')";

if(!$database->queryExec($query, $error))
{
  die($error);
}
else
echo "1";

