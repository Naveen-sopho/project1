<?php

require("mysqlconnect.php");

if (empty($_GET['q']))
{
  echo "Please Enter URL.";
  exit;

}
mysqli_query($dbconnect, "TRUNCATE TABLE colleges");
$query = $_GET['q'];
echo $query;
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
$size = sizeof($matches[0],1);
//storing scraped data
for($j=0;$j<$size;$j++)
{
 
  preg_match_all('@<h2 class="tuple-clg-heading"><a href="[^"]+" target="[^"]+">\s*([^<]+)<\/a>@', $matches[0][$j], $c); 
  preg_match_all('@<p>\|\s*([^<]+)<\/p><\/h2>@' , $matches[0][$j], $ca);
  preg_match_all('@<div class="srpHoverCntnt2"><h3>\s*([^<]+)<\/h3>@', $matches[0][$j], $f);
  $college_name[$j] = $c[1][0];
  $college_address[$j] = $ca[1][0];
  $facilities[$j] = implode("|", $f[1]);
}
//array declaration

print_r($matches);
$r = [];
$reviews = [];
for($k=0;$k<$size;$k++)
{
  preg_match_all('/<div class="tuple-revw-sec"><span><b>\s*([^<]+)<\/b><a target="[^"]+"\stype="reviews/i',$matches[0][$k],$r);

  if(!$r[1])
    $reviews[$k] = "0";
  else
    $reviews[$k] = $r[1][0];
}
//checking arrays
//print_r($college_name);
//print_r($college_address);
//print_r($facilities);
//print_r($reviews);

for($s=0;$s<$size;$s++)
{
    mysqli_query($dbconnect,"SELECT * FROM colleges");
    mysqli_query($dbconnect,"INSERT INTO colleges (college_name,college_address,facilities,reviews) VALUES(\"".$college_name[$s]."\",\"".$college_address[$s]."\",\"".$facilities[$s]."\",\"".$reviews[$s]."\")");
}

 mysqli_close($dbconnect);

?>

