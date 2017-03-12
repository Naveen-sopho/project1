<?php
require("mysqlconnect.php");
//$dbconnect = mysqli_connect('localhost', 'naveen_palnaty', 'v1kCjsvLYytrBTGV','project1');
////if (!$dbconnect) {
    //die('Could not connect: ' . mysql_error());
//}
if (empty($_GET['q']))
{
    echo "Please Enter URL.";
    exit;
    
}
$query = $_GET['q'];

$wet=file_get_contents($_GET['q']);
//echo $wet;

$regex='@<h2 class="tuple-clg-heading"><a href="[^"]+" target="[^"]+">\s*([^<]+)<\/a>\n<p>\|\s*([^<]+)<\/p><\/h2>@';
$regex2='@<div class="srpHoverCntnt2">\n<h3>\s*([^<]+)<\/h3>@';
$regex3='@<div class="tuple-revw-sec">\n<span><b>\s*([^<]+)<\/b><a target="[^"]+"\stype="reviews"@';
if (!preg_match_all($regex, $wet, $matches)) 
{
  echo "No matches found";
  exit;
}
if (!preg_match_all($regex2, $wet, $faci)) 
{
  echo "No matches found";
  exit;
}
if (!preg_match_all($regex3, $wet, $review)) 
{
  echo "No matches found";
  exit;
}

$titles = $matches[1];
$college_address = $matches[2];

$college_facilities = $faci[1];
$college_reviews = $review[1];
echo "<b>The following titles matched your query for '" . htmlspecialchars($query) . "':</b><br />";
foreach($titles as $title) 
{
  mysqli_query($dbconnect,"SELECT * FROM colleges");
  mysqli_query($dbconnect,"INSERT INTO colleges (college_name) VALUES(\"".$title."\")");

}
foreach($college_address as $add)
{
    mysqli_query($dbconnect,"SELECT * FROM college_address");
    mysqli_query($dbconnect,"INSERT INTO college_address (college_add) VALUES(\"".$add."\")");
}
  mysqli_close($dbconnect);

?>