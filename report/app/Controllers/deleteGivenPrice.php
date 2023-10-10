<?php
session_start();
require_once('../../database/connect.php');
require_once('../../utilities/helper.php');

if (isset($_POST['delete_price'])) {
    $id = $_POST['id'];
    $partNumber = $_POST['partNumber'];
    $customer_id = $_POST['customer_id'];
    $notification_id = $_POST['notification_id'];
    $relation_id = $_POST['relation_id'];
    $code = $_POST['code'];
    $sql = "DELETE FROM `prices` WHERE id = '$id'";
    mysqli_query($conn, $sql);

    $sql = "SELECT id, partnumber FROM yadakshop1402.nisha WHERE partnumber = '$partNumber'";
    $result = mysqli_query($conn, $sql);
    $good = $result->fetch_assoc();

    $relation_exist = isInRelation($conn, $good['id']);
    $relations = relations($conn, $good['id']);
    $relations = array_keys($relations['goods']);

    $givenPrice = givenPrice($conn, $relations, $relation_exist);

    if ($givenPrice !== null) {
        $target = current($givenPrice);
        $priceDate = $target['created_at'];
        if (checkDateIfOkay($applyDate, $priceDate) && $target['price'] !== 'موجود نیست') :
            $rawGivenPrice = $target['price'];

            $finalPrice = applyDollarRate($rawGivenPrice);
?>
            <tr class="min-w-full mb-1  bg-cyan-400 hover:cursor-pointer">
                <td>
                </td>
                <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?php echo $code ?>" data-price="<?= $finalPrice ?>" data-part="<?php echo $partNumber ?>" scope="col" class="relative text-center text-gray-800 px-2 py-1 <?php echo array_key_exists("ordered", $target) || $target['customerID'] == 1 ? 'text-white' : '' ?>">
                    <?php echo $target['price'] === null ? 'ندارد' :  $finalPrice ?>
                </td>
                <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?php echo $code ?>" data-price="<?= $finalPrice ?>" data-part="<?php echo $partNumber ?>" scope="col" class="text-center text-gray-800 px-2 py-1 rtl <?php echo array_key_exists("ordered", $target) || $target['customerID'] == 1 ? 'text-white' : '' ?>">
                    افزایش قیمت <?= $additionRate ?> در صد
                </td>
                <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?php echo $code ?>" data-price="<?= $finalPrice ?>" data-part="<?php echo $partNumber ?>" class="bold <?php echo array_key_exists("ordered", $target) || $target['customerID'] == 1 ? 'text-white' : '' ?> ">
                    <?php echo array_key_exists("partnumber", $target) ? $target['partnumber'] : '' ?>
                </td>
                <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?php echo $code ?>" data-price="<?= $finalPrice ?>" data-part="<?php echo $partNumber ?>" scope="col" class="text-center text-gray-800 px-2 py-1 rtl <?php echo array_key_exists("ordered", $target) || $target['customerID'] == 1 ? 'text-white' : '' ?>">
                    <?php if (!array_key_exists("ordered", $target)) {
                    ?>
                        <img class="userImage" src="../../userimg/<?php echo $target['userID'] ?>.jpg" alt="userimage">
                    <?php
                    }
                    ?>
                </td>
            </tr>
            <?php
        endif;
        foreach ($givenPrice as $price) {
            if ($price['price'] !== null && $price['price'] !== '') {
                if (array_key_exists("ordered", $price) || $price['customerID'] == 1) { ?>
                    <tr class="min-w-full mb-1  bg-red-400 hover:cursor-pointer">
                    <?php } elseif (array_key_exists("ordered", $price) || $price['customerID'] == 2) { ?>
                    <tr class="min-w-full mb-1 bg-slate-300 hover:cursor-pointer">
                    <?php  } else {
                    ?>
                    <tr class="min-w-full mb-1  bg-indigo-200 hover:cursor-pointer">
                    <?php
                }
                if (array_key_exists("id", $price)) : ?>
                        <td onclick="deleteGivenPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-part="<?= $partNumber ?>" data-del='<?= $price['id'] ?>' scope="col" class="text-center text-gray-800 px-2 py-1 <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                            <i id="deleteGivenPrice" class="material-icons" title="حذف قیمت">close</i>
                        </td>
                    <?php else : ?>
                        <td></td>
                    <?php endif; ?>
                    <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $price['price'] ?>" data-part="<?= $partNumber ?>" scope="col" class="relative text-center text-gray-800 px-2 py-1 <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                        <?= $price['price'] === null ? 'ندارد' : $price['price']  ?>
                    </td>
                    <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $price['price'] ?>" data-part="<?= $partNumber ?>" scope="col" class="text-center text-gray-800 px-2 py-1 rtl <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                        <?php if (array_key_exists("ordered", $price)) {
                            echo 'قیمت دستوری';
                        } else {
                            echo $price['name'] . ' ' . $price['family'];
                        }
                        ?>
                    </td>
                    <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $price['price'] ?>" data-part="<?= $partNumber ?>" class="bold <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?> ">
                        <?= array_key_exists("partnumber", $price) ? $price['partnumber'] : '' ?>
                    </td>
                    <td onclick="setPrice(this)" data-target="<?= $relation_id ?>" data-code="<?= $code ?>" data-price="<?= $price['price'] ?>" data-part="<?= $partNumber ?>" scope="col" class="text-center text-gray-800 px-2 py-1 rtl <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : '' ?>">
                        <?php if (!array_key_exists("ordered", $price)) {
                        ?>
                            <img class="userImage" src="../../userimg/<?= $price['userID'] ?>.jpg" alt="userimage">
                        <?php
                        }
                        ?>
                    </td>
                    </tr>
                    <?php if (array_key_exists("ordered", $price) || $price['customerID'] == 1) { ?>
                        <tr class="min-w-full mb-1 border-b-2 bg-red-500">
                            <td></td>
                            <td class="<?php array_key_exists("ordered", $price) ? 'text-white' : '' ?> text-gray-800 px-2 tiny-text" colspan="4" scope="col">
                                <div class="rtl flex items-center w-full <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : 'text-gray-800' ?>">
                                    <i class="px-1 material-icons tiny-text <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : 'text-gray-800' ?>">access_time</i>
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
                    <?php } elseif (array_key_exists("ordered", $price) || $price['customerID'] == 2) { ?>
                        <tr class="min-w-full mb-1 bg-slate-400 hover:cursor-pointer">
                            <td></td>
                            <td class="<?php array_key_exists("ordered", $price) ? 'text-white' : '' ?> text-gray-800 px-2 tiny-text" colspan="4" scope="col">
                                <div class="rtl flex items-center w-full <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : 'text-gray-800' ?>">
                                    <i class="px-1 material-icons tiny-text <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : 'text-gray-800' ?>">access_time</i>
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
                    <?php  } else {
                    ?>
                        <tr class="min-w-full mb-1  bg-indigo-300 hover:cursor-pointer">
                            <td></td>
                            <td class="<?php array_key_exists("ordered", $price) ? 'text-white' : '' ?> text-gray-800 px-2 tiny-text" colspan="4" scope="col">
                                <div class="rtl flex items-center w-full <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : 'text-gray-800' ?>">
                                    <i class="px-1 material-icons tiny-text <?= array_key_exists("ordered", $price) || $price['customerID'] == 1 ? 'text-white' : 'text-gray-800' ?>">access_time</i>
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
            <?php
                    }
                }
            }
        } else { ?>
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
        $sql = "SELECT  prices.id, prices.price, prices.partnumber, customer.name, customer.id AS customerID, customer.family, users.id AS userID, prices.created_at
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

    /**
     * @param Connection to the database
     * @param int $id is the id of the good to check if it has a relationship
     * @return int if the good has a relationship return the id of the relationship
     */
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
