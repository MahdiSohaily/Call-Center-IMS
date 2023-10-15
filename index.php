 <?php require_once './layout/heroHeader.php'; ?>

 <div class="box user-table">

     <h2 class="title">لیست داخلی کاربران</h2>
     <table class="customer-list user-inter-table">
         <tr>
             <th>کاربر</th>
             <th>داخلی</th>
             <th>آی پی</th>
         </tr>
         <?php
            $sql = "SELECT * FROM users ORDER BY internal";
            $result = mysqli_query(dbconnect2(), $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['id'];
                    $name = $row['name'];
                    $family = $row['family'];
                    $internal = $row['internal'];
                    $ip = $row['ip'];

                    if (!$internal) {

                        continue;
                    }
            ?>
                 <?php
                    $profile = '../userimg/default.png';
                    if (file_exists("../userimg/" . $id . ".jpg")) {
                        $profile = "../userimg/" . $id . ".jpg";
                    }
                    ?>
                 <tr>
                     <td><?= $name ?> <?= $family ?><img class="user-img" src="<?= $profile ?>" /></td>
                     <td class="user-table-internal"><span><?= $internal ?></span></td>
                     <td class="user-table-ip"><span><?= $ip ?></span></td>
                 </tr>
         <?php

                }
            }

            ?>
     </table>
 </div>


 <div class="box user-table">
     <h2 class="title">مدت زمان مکالمه</h2>
     <?php
        $datetime101 = new DateTime('2019-09-30 00:00:00');
        $datetime102 = new DateTime('2019-09-30 00:00:00');
        $datetime103 = new DateTime('2019-09-30 00:00:00');
        $datetime104 = new DateTime('2019-09-30 00:00:00');
        $datetime106 = new DateTime('2019-09-30 00:00:00');
        $datetime107 = new DateTime('2019-09-30 00:00:00');
        $datetimeMarjae = new DateTime('2019-09-30 00:00:00');

        $sql = "SELECT * FROM incoming WHERE starttime IS NOT NULL AND time >= CURDATE() ";
        $result = mysqli_query($con, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $user = $row['user'];
                $phone = $row['phone'];
                $starttime = $row['starttime'];
                $endtime = $row['endtime'];
                $xxx =   nishatimedef($starttime, $endtime);

                if ($user == 101) {
                    $datetime101->add($xxx);
                }
                if ($user == 102) {
                    $datetime102->add($xxx);
                }
                if ($user == 103) {
                    $datetime103->add($xxx);
                }
                if ($user == 104) {
                    $datetime104->add($xxx);
                }
                if ($user == 106) {
                    $datetime106->add($xxx);
                }
                if ($user == 107) {
                    $datetime107->add($xxx);
                }
            }
        }
        $total101 = $datetimeMarjae->diff($datetime101);
        $total102 = $datetimeMarjae->diff($datetime102);
        $total103 = $datetimeMarjae->diff($datetime103);
        $total104 = $datetimeMarjae->diff($datetime104);
        $total106 = $datetimeMarjae->diff($datetime106);
        $total107 = $datetimeMarjae->diff($datetime107);


        ?>
     <table class="customer-list user-time-dur-table">
         <tr>
             <th>کاربر</th>
             <th>مدت زمان مکالمه</th>


         </tr>

         <tr>
             <td> <img class="user-img" src="../userimg/<?= getidbyinternal(101) ?>.jpg" /></td>

             <td><?= format_calling_time($total101) ?></td>

         </tr>
         <tr>
             <td> <img class="user-img" src="../userimg/<?= getidbyinternal(102) ?>.jpg" /></td>

             <td><?= format_calling_time($total102) ?></td>

         </tr>
         <tr>
             <td> <img class="user-img" src="../userimg/<?= getidbyinternal(103) ?>.jpg" /></td>
             <td><?= format_calling_time($total103) ?></td>

         </tr>
         <tr>
             <td> <img class="user-img" src="../userimg/<?= getidbyinternal(104) ?>.jpg" /></td>
             <td><?= format_calling_time($total104) ?></td>

         </tr>
         <tr>
             <td> <img class="user-img" src="../userimg/<?= getidbyinternal(106) ?>.jpg" /></td>
             <td><?= format_calling_time($total106) ?></td>

         </tr>
         <tr>
             <td> <img class="user-img" src="../userimg/<?= getidbyinternal(107) ?>.jpg" /></td>
             <td><?= format_calling_time($total107) ?></td>

         </tr>

     </table>



 </div>

 <?php

    require_once './layout/heroFooter.php';
