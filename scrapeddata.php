   <!doctype html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>database connections</title>
    </head>
    <body>
<?php

require("mysqlconnect.php");

if (empty($_GET['q']))
{
  echo "Please Enter URL.";
  exit;

}
mysqli_query($dbconnect, "TRUNCATE TABLE colleges");
$query = $_GET['q'];
//echo $query;

//$wet=file_get_contents($_GET['q']);
$ch = curl_init();
$curlConfig = array(
  CURLOPT_URL            => $query,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_BINARYTRANSFER => true,
  CURLOPT_SSL_VERIFYPEER => false
);
curl_setopt_array($ch, $curlConfig);
$wet = curl_exec($ch);
curl_close($ch);
$wet = preg_replace("/\n/i", "", $wet);
//print_r("<pre>");
if(!preg_match_all('/<div class="clg-tpl-parent">(.*?)<input type="hidden"/i', $wet, $matches))
{
 // echo "<h1 align="center">No colleges found </h1>" ;
 echo "NO colleges found" ;
}

//array declaration
$c = [];
$college_name = [];
$ca = [];
$college_address = [];
$f = [];
$facilities = [];
$size = sizeof($matches[0],1);
//print_r($matches);
//storing scraped data
for($j=0;$j<$size;$j++)
{
 
  preg_match_all('@<h2 class="tuple-clg-heading"><a href="[^"]+" target="[^"]+">\s*([^<]+)<\/a>@', $matches[1][$j], $c); 
  preg_match_all('@<p>\|\s*([^<]+)<\/p><\/h2>@' , $matches[0][$j], $ca);
  preg_match_all('@<div class="srpHoverCntnt2"><h3>\s*([^<]+)<\/h3>@', $matches[0][$j], $f);
  $college_name[$j] = $c[1][0];
  $college_address[$j] = $ca[1][0];
  $facilities[$j] = implode("|", $f[1]);
}
//array declaration
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

for($s=0;$s<$size+1;$s++)
{
   $result =  mysqli_query($dbconnect,"SELECT * FROM colleges");
    mysqli_query($dbconnect,"INSERT INTO colleges (college_name,college_address,facilities,reviews) VALUES(\"".$college_name[$s]."\",\"".$college_address[$s]."\",\"".$facilities[$s]."\",\"".$reviews[$s]."\")");
}

 

?>
<table border = "1" width ="100%" >
      <thead border="1" style= "background-color: #585e68; color: #e5e7ea; margin: 0 auto;">
        <tr>
          <th>COLLEGE NAME</th>
          <th>COLLEGE ADDRESS</th>
          <th>FACILITIES</th>
          <th>NO OF REVIEWS</th>
        </tr>
      </thead>
      <tbody border="1" style= "background-color: #cccccc; color: #000d23; margin: 0 auto;">
        <?php
        if(!preg_match_all('/<div class="clg-tpl-parent">(.*?)<input type="hidden"/i', $wet, $matches))
        {
          echo "";
          
        }
        else
        {
          while( $row = mysqli_fetch_assoc( $result ) )
          {
            echo
            "<tr>
              <td>{$row['college_name']}</td>
              <td>{$row['college_address']}</td>
              <td>{$row['facilities']}</td>
              <td>{$row['reviews']}</td>
            </tr>\n";
          }
        }
        ?>
      </tbody>
    </table>
     <?php mysqli_close($dbconnect); ?>
    </body>
    </html>
