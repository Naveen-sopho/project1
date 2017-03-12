<?php
$dbconnect = mysqli_connect('localhost', 'naveen_palnaty', 'v1kCjsvLYytrBTGV','project1');
if (!$dbconnect) {
    die('Could not connect: ' . mysql_error());
}
//mysqli_select_db($dbconnect,"project1");
?>