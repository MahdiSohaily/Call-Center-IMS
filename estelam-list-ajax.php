<?php
$sql2 = "SELECT * FROM estelam ORDER BY  time DESC LIMIT 250  ";
$result2 = mysqli_query($con, $sql2);
if (mysqli_num_rows($result2) > 0) {
    while ($row2 = mysqli_fetch_assoc($result2)) {

        $code = $row2['codename'];
        $seller = $row2['seller'];
        $price = $row2['price'];
        $user = $row2['user'];
        $time = $row2['time'];


        $sql = "SELECT * FROM users WHERE id=$user";
        $result = mysqli_query(dbconnect2(), $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $name = $row['name'];
                $family = $row['family'];



                $sql3 = "SELECT * FROM seller WHERE id=$seller";
                $result3 = mysqli_query(dbconnect2(), $sql3);
                if (mysqli_num_rows($result3) > 0) {
                    while ($row3 = mysqli_fetch_assoc($result3)) {

                        $sellername = $row3['name'];
?>
                        <tr>
                            <td><?php echo $code ?></td>
                            <td><?php echo $sellername ?></td>
                            <td><?php echo $price ?></td>
                            <td><?php echo $name ?> <?php echo $family ?></td>
                            <td><?php echo $time ?></td>
                        </tr>
<?php
                    }
                }
            }
        }
    }
}
?>