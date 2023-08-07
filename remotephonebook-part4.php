<?php
if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml")) {
    header("Content-type: application/xhtml+xml");
    echo '<?xml version="1.0" encoding="UTF-8"?>';
}
else { header("Content-type: text/html"); }

global $con;
$con = mysqli_connect('localhost','root','','callcenter');
if (!$con) {
die('Could not connect: ' . mysqli_error($con));
}
mysqli_set_charset($con, "utf8");

?>

<YeastarIPPhoneDirectory>
    <?php

$sql="SELECT * FROM customer LIMIT 13500,4500 ";
$result = mysqli_query($con,$sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$name = $row['name'];
$family = $row['family'];
$phone = $row['phone'];
 
?>

    <DirectoryEntry>
        <Name><?php echo($name." ".$family)?></Name>
        <Telephone label="Mobile Number"><?php echo $phone ?></Telephone>
    </DirectoryEntry>


    <?php
}}
?>




</YeastarIPPhoneDirectory>
