<?php
session_start();
require_once('../../database/connect.php');
if (isset($_POST['delete_price'])) {
    $partnumber = $_POST['partNumber'];
    $price = $_POST['price'];
    $customer_id = $_POST['customer_id'];
    $notification_id = $_POST['notification_id'];
    $code = $_POST['code'];
    $sql = "DELETE FROM `price` WHERE id = '$id'";

    $sql = "SELECT id, partnumber FROM yadakshop1402.nisha WHERE partnumber = '$partnumber'";
    $result = mysqli_query($conn, $sql);
    $good = $result->fetch_assoc();

    $relation_exist = isInRelation($conn, $good['id']);
    $relations = relations($conn, $good['id']);
    $relations = array_keys($relations['goods']);

    $givenPrice = givenPrice($conn, $relations, $relation_exist);

    if ($givenPrice !== null) {
        foreach ($givenPrice as $price) {
            if ($price['price'] !== null && $price['price'] !== '') {
                if (array_key_exists("ordered", $price) || $price['customerID'] == 1) { ?>
                    <tr class="min-w-full mb-1  bg-red-400 hover:cursor-pointer" onclick="setPrice(this)" data-code="<?php echo $code ?>" data-price="<?php echo $price['price'] ?>" data-part="<?php echo $partnumber ?>">
                    <?php } else { ?>
                    <tr class="min-w-full mb-1  bg-indigo-200 hover:cursor-pointer" onclick="setPrice(this)" data-code="<?php echo $code ?>" data-price="<?php echo $price['price'] ?>" data-part="<?php echo $partnumber ?>">
                    <?php  } ?>
                    <td scope="col" class="relative text-center text-gray-800 px-2 py-1 <?php echo array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                        <?php echo $price['price'] === null ? 'ندارد' : $price['price']  ?>
                        <i id="deleteGivenPrice" class="material-icons" title="حذف قیمت" data-part="<?php echo $partNumber ?>" data-code="<?php echo $code ?>" onclick="deleteGivenPrice(this)" data-del='<?php echo $price['id'] ?>'>close</i>
                    </td>
                    <td scope="col" class="text-center text-gray-800 px-2 py-1 rtl <?php echo array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                        <?php if (array_key_exists("ordered", $price)) {
                            echo 'قیمت دستوری';
                        } else {
                            echo $price['name'] . ' ' . $price['family'];
                        }
                        ?>
                    </td>
                    <td class="bold <?php echo array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?> ">
                        <?php echo array_key_exists("partnumber", $price) ? $price['partnumber'] : '' ?>
                    </td>
                    <td scope="col" class="text-center text-gray-800 px-2 py-1 rtl <?php echo array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                        <?php if (!array_key_exists("ordered", $price)) {
                        ?>
                            <img class="userImage" src="../../userimg/<?php echo $price['userID'] ?>.jpg" alt="userimage">
                        <?php
                        }
                        ?>
                    </td>
                    </tr>
                    <tr class="min-w-full mb-1 border-b-2 <?php echo array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'bg-red-500' : 'bg-indigo-300' ?>" data-price='<?php echo $price['price'] ?>'>
                        <td></td>
                        <td class="<?php array_key_exists("ordered", $price) ? 'text-white' : '' ?> text-gray-800 px-2 tiny-text" colspan="3" scope="col">
                            <div class="rtl flex items-center w-full <?php echo array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : 'text-gray-800' ?>">
                                <i class="px-1 material-icons tiny-text <?php echo array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : 'text-gray-800' ?>">access_time</i>
                                <?php
                                $create = date($price['created_at']);


                                $now = new DateTime(); // current date time
                                $date_time = new DateTime($create); // date time from string
                                $interval = $now->diff($date_time); // difference between two date times
                                $days = $interval->format('%a'); // difference in days
                                $hours = $interval->format('%h'); // difference in hours
                                $minutes = $interval->format('%i'); // difference in minutes
                                $seconds = $interval->format('%s'); // difference in seconds

                                $text = '';

                                if ($days) {
                                    $text .= " $days روز و ";
                                }

                                if ($hours) {
                                    $text .= "$hours ساعت ";
                                }

                                if (!$days && $minutes) {
                                    $text .= "$minutes دقیقه ";
                                }

                                if (!$days && !$hours && $seconds) {
                                    $text .= "$seconds ثانیه ";
                                }

                                echo "$text قبل";
                                ?>
                            </div>
                        </td>
                    </tr>

            <?php }
        } ?>
        <?php } else { ?>
            <tr class="min-w-full mb-4 border-b-2 border-white">
                <td colspan="3" scope="col" class="text-gray-800 py-2 text-center bg-indigo-300">
                    !! موردی برای نمایش وجود ندارد
                </td>
            </tr>
        <?php } ?>
    <?php

}

    ?>

    <?php

    function relations($conn, $id)
    {
        $sql = "SELECT pattern_id FROM similars WHERE nisha_id = '" . $id . "'";
        $result = mysqli_query($conn, $sql);

        $isInRelation = null;
        if (mysqli_num_rows($result) > 0) {
            $isInRelation = mysqli_fetch_assoc($result);
        }

        $relations = [];

        if ($isInRelation) {

            $sql = "SELECT yadakshop1402.nisha.* FROM yadakshop1402.nisha INNER JOIN similars ON similars.nisha_id = nisha.id WHERE similars.pattern_id = '" . $isInRelation['pattern_id'] . "'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($info = mysqli_fetch_assoc($result)) {
                    array_push($relations, $info);
                }
            }
        } else {
            $sql = "SELECT * FROM yadakshop1402.nisha WHERE id = '" . $id . "'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $relations[0] = mysqli_fetch_assoc($result);
            }
        }


        $sortedGoods = [];
        foreach ($relations as $relation) {
            $sortedGoods[$relation['partnumber']] = $relation;
        }

        return ['goods' => $sortedGoods];
    }

    function isInRelation($conn, $id)
    {
        $sql = "SELECT pattern_id FROM similars WHERE nisha_id = '$id'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($item = mysqli_fetch_assoc($result)) {
                return $item['pattern_id'];
            }
        }
        return false;
    }

    function givenPrice($conn, $codes, $relation_exist = null)
    {
        $codes = array_filter($codes, function ($item) {
            return strtolower($item);
        });
        $ordared_price = [];


        if ($relation_exist) {
            $out_sql = "SELECT patterns.price, patterns.created_at FROM patterns WHERE id = '" . $relation_exist . "'";
            $out_result = mysqli_query($conn, $out_sql);

            if (mysqli_num_rows($out_result) > 0) {
                $ordared_price = mysqli_fetch_assoc($out_result);
            }
            $ordared_price['ordered'] = true;
        }

        $givenPrices = [];
        $sql = "SELECT prices.price, prices.partnumber, customer.name, customer.id AS customerID, customer.family, users.id AS userID, prices.created_at
        FROM ((prices 
        INNER JOIN callcenter.customer ON customer.id = prices.customer_id)
        INNER JOIN yadakshop1402.users ON users.id = prices.user_id)
        WHERE partnumber IN ('" . implode("','", $codes) . "')
        ORDER BY created_at DESC LIMIT 7";

        $result = mysqli_query($conn, $sql);
        while ($item = mysqli_fetch_assoc($result))
            array_push($givenPrices, $item);

        $givenPrices = array_filter($givenPrices, function ($item) {

            if ($item !== null && count($item) > 0) {
                return $item;
            }
        });

        $unsortedData = [];
        foreach ($givenPrices as $item) {
            array_push($unsortedData, $item);
        }

        array_push($unsortedData, $ordared_price);

        if ($relation_exist) {
            usort($unsortedData, function ($a, $b) {
                return $a['created_at'] < $b['created_at'];
            });
        }
        $final_data = $relation_exist ? $unsortedData : $givenPrices;

        return  $final_data;
    }

    if (isset($_POST['askPrice'])) {
        $partnumber = $_POST['partNumber'];
        $customer_id = $_POST['customer_id'];
        $user_id = $_POST['user_id'];
        date_default_timezone_set("Asia/Tehran");
        $created_at = date("Y-m-d H:i:s");

        askPrice($conn, $partnumber, $customer_id, $user_id, $created_at);
    }

    function store($conn, $partnumber, $price, $customer_id, $notification_id)
    {
        date_default_timezone_set("Asia/Tehran");
        $created_at = date("Y-m-d H:i:s");
        $pattern_sql = "INSERT INTO prices (partnumber, price, user_id, customer_id, created_at, updated_at)
            VALUES ('" . $partnumber . "', '" . $price . "','" . $_SESSION["id"] . "' ,'" . $customer_id . "', '" . $created_at . "', '" . $created_at . "')";
        $conn->query($pattern_sql);
        if ($notification_id) {
            $sql = "UPDATE ask_price SET status= 'done' , notify = 'received', price = '$price' WHERE id = '$notification_id'";
            $conn->query($sql);
        }
    }


    function askPrice($conn, $partnumber, $customer_id, $user_id, $created_at)
    {
        $pattern_sql = "INSERT INTO ask_price (customer_id, user_id, code, status, notify, created_at)
            VALUES ('" . $customer_id . "', '" . $user_id . "', '" . $partnumber . "', 'pending', 'send' , '" . $created_at . "')";

        if ($conn->query($pattern_sql) === TRUE) {
            echo 'true';
        }
    }
