   <!doctype html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Search colleges</title>
    </head>
    <body>
<?php
//connecting with sql
require("mysqlconnect.php");

if (empty($_GET['q']))
{
  echo "Please Enter URL.";
  exit;

}
//truncating the previous table in db
mysqli_query($dbconnect, "TRUNCATE TABLE colleges");

//getting url
$query = $_GET['q'];

//getting contents using curl
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

//replacing new lines from the code
$wet = preg_replace("/\n/i", "", $wet);

//Getting colleges data in a page 
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
//number of colleges present
$size = sizeof($matches[0],1);
//scraping the required data
for($j=0;$j<$size;$j++)
{
 //college names
  preg_match_all('@<h2 class="tuple-clg-heading"><a href="[^"]+" target="[^"]+">\s*([^<]+)<\/a>@', $matches[1][$j], $c);
  //college address
  preg_match_all('@<p>\|\s*([^<]+)<\/p><\/h2>@' , $matches[0][$j], $ca);
  //facilities
  preg_match_all('@<div class="srpHoverCntnt2"><h3>\s*([^<]+)<\/h3>@', $matches[0][$j], $f);
  $college_name[$j] = $c[1][0];
  $college_address[$j] = $ca[1][0];
  //chaniging array (facilities) into a string
  $facilities[$j] = implode("|", $f[1]);
}
//array declaration
$r = [];
$reviews = [];
for($k=0;$k<$size;$k++)
{
  //no of reviews
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

//adding scrapped data to db
for($s=0;$s<$size+1;$s++)
{
   $result =  mysqli_query($dbconnect,"SELECT * FROM colleges");
    mysqli_query($dbconnect,"INSERT INTO colleges (college_name,college_address,facilities,reviews) VALUES(\"".$college_name[$s]."\",\"".$college_address[$s]."\",\"".$facilities[$s]."\",\"".$reviews[$s]."\")");
}

 //html page with a table of scrapped data

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
     <?php 
     //closing db connnection
     mysqli_close($dbconnect);
     ?>
    </body>
    </html>
