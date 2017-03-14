<?php

//}

if (empty($_GET['q']))
{
  echo "Please Enter URL.";
  exit;

}
$query = $_GET['q'];

$wet=file_get_contents($_GET['q']);
$wet = preg_replace("/\n/i", "", $wet);
print_r("<pre>");
preg_match_all('/<div class="clg-tpl-parent">(.*?)<input type="hidden"/i', $wet, $matches);
//array declaration
$c = [];
$college_name = [];
$ca = [];
$college_address = [];
$f = [];
$facilities = [];
//storing scraped data
for($j=0;$j<30;$j++)
{
 
  preg_match_all('@<h2 class="tuple-clg-heading"><a href="[^"]+" target="[^"]+">\s*([^<]+)<\/a>@', $matches[0][$j], $c); 
  preg_match_all('@<p>\|\s*([^<]+)<\/p><\/h2>@' , $matches[0][$j], $ca);
  preg_match_all('@<div class="srpHoverCntnt2"><h3>\s*([^<]+)<\/h3>@', $matches[0][$j], $f);
  $college_name[$j] = $c[1][0];
  $college_address[$j] = $ca[1][0];
  $facilities[$j] = implode("|", $f[1]);
}
//checking arrays
print_r($college_name);
print_r($college_address);
print_r($facilities);



//echo "<b>The following titles matched your query for '" . htmlspecialchars($query) . "':</b><br />";
//foreach($titles as $title) 
// {
//  mysqli_query($dbconnect,"SELECT * FROM colleges");
//  mysqli_query($dbconnect,"INSERT INTO colleges (college_name) VALUES(\"".$title."\")");
//
//}
//foreach($college_address as $add)
//{
//   mysqli_query($dbconnect,"SELECT * FROM college_address");
//    mysqli_query($dbconnect,"INSERT INTO college_address (college_add) VALUES(\"".$add."\")");
//}
//  mysqli_close($dbconnect);
//

?>