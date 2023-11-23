 <?php
    require_once './layout/heroHeader.php';
    require_once './utilities/helpers.php';
    ?>
 <style>
     .user-imgs {
         display: inline-block;
         width: 40px;
         height: 40px;
         border-radius: 50%;
         margin-inline: auto;
     }

     .circle-frame {
         display: flex;
         justify-content: center;
         align-items: center;
         border-radius: 50%;
         background-color: black;
         color: white;
         font-weight: bold;
         width: 40px;
         height: 40px;
         margin-inline: auto;
     }
 </style>
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
     <table class="customer-list user-time-dur-table">
         <tr>
             <th>کاربر</th>
             <th>مدت زمان مکالمه</th>


         </tr>

         <?php
            foreach ($datetimeData as $key => $value) :
                $file = "../userimg/" . getidbyinternal($key) . ".jpg";
                if (file_exists($file)) :
            ?>
                 <tr>
                     <td> <img class="user-imgs" src="../userimg/<?= getidbyinternal($key) ?>.jpg" /></td>
                 <?php else : ?>
                     <td>
                         <p class="circle-frame">
                             <?= $key ?>
                         </p>
                     </td>
                 <?php
                endif;
                    ?>
                 <td style='text-align: center;'>
                     <?= format_calling_time_seconds($value['total']) ?>
                 </td>
                 </tr>
             <?php
            endforeach;
                ?>

     </table>
 </div>

 <?php

    require_once './layout/heroFooter.php';
