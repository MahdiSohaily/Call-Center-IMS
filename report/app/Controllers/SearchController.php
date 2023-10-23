<?php
require_once('../../database/connect.php');
if (isset($_POST['pattern'])) {
    $pattern = $_POST['pattern'];
    $sql = "SELECT * FROM yadakshop1402.nisha WHERE partnumber LIKE '" . $pattern . "%'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            $rates_sql = "SELECT * FROM rates ORDER BY amount ASC";
            $rates = mysqli_query($conn, $rates_sql);

            $partNumber = $item['partnumber'];
            $price = $item['price'];
            $avgPrice = round((intval($price) * 110) / 243.5);
            $weight = round(intval($item['weight']), 2);
            $mobis = $item['mobis'];
            $korea = $item['korea'];
            $status = null;

            if ($mobis == "0.00") {
                $status = "NO-Price";
            } elseif ($mobis == "-") {
                $status = "NO-Mobis";
            } elseif ($mobis == NULL) {
                $status = "Request";
            } else {
                $status = "YES-Mobis";
            }
?>

            <tr class="transition duration-300 ease-in-out hover:bg-neutral-200">
                <td class='whitespace-nowrap bg-blue-900'>
                    <div class='flex gap-1 text-white font-bold'>
                        <?php if ($status == "Request") { ?>
                            <a class='link-s ml-4 Request' target='_blank' href='./mobis.php?partNumber=<?php echo $partNumber ?>'>?</a>
                        <?php
                        } else if ($status == "NO-Price") { ?>
                            <a class='link-s ml-4 NO-Price' target='_blank' href='./mobis.php?partNumber=<?php echo $partNumber ?>'>!</a>
                        <?php
                        } else if ($status == "NO-Mobis") { ?>
                            <a class='link-s ml-4 NO-Mobis' target='_blank' href='./mobis.php?partNumber=<?php echo $partNumber ?>'>x</a>
                        <?php
                        } else { ?> <span class='ml-11'></span>
                        <?php } ?>
                        <?php echo $partNumber ?>
                    </div>
                </td>
                <td class='whitespace-nowrap text-center px-3 py-3'>
                    <?php echo round($avgPrice * 1.1) ?>
                </td>
                <td class='orange whitespace-nowrap text-center px-3 py-3 border-black border-r-2'>
                    <?php echo round($avgPrice * 1.2) ?>
                </td>
                <?php
                if (mysqli_num_rows($rates) > 0) {
                    while ($rate = mysqli_fetch_assoc($rates)) {
                ?>
                        <td class='whitespace-nowrap px-3 py-3 text-center <?php echo $rate['status'] ?>'>
                            <?php echo  round($avgPrice * $rate['amount'] * 1.2 * 1.2 * 1.3) ?>
                        </td>
                <?php
                    }
                }
                ?>
                <td class='whitespace-nowrap w-24'>
                    <div class='flex justify-center gap-1 items-center px-2'>
                        <a target='_blank' href='https://www.google.com/search?tbm=isch&q=<?php echo $partNumber ?>'>
                            <img class='w-5 h-auto' src='./public/img/google.png' alt='google'>
                        </a>
                        <a msg=' <?php echo $partNumber ?>'>
                            <img class='w-5 h-auto' src='./public/img/tel.png' alt='part'>
                        </a>
                        <a target='_blank' href='https://partsouq.com/en/search/all?q=<?php echo $partNumber ?>'>
                            <img class='w-5 h-auto' src='./public/img/part.png' alt='part'>
                        </a>
                    </div>
                </td>
                <td class='whitespace-nowrap px-3 py-3 kg'>
                    <div class='weight'><?php echo $weight ?>KG</div>
                </td>
            </tr>
            <?php
            if ($status == "YES-Mobis") {
                $price = $mobis;
                $price = str_replace(",", "", $price);
                $avgPrice = round(($price * 110) / 243.5);
                $rates_sql = "SELECT * FROM rates ORDER BY amount ASC";
                $rates = mysqli_query($conn, $rates_sql);
            ?>
                <tr class='mobis transition duration-400 ease-in-out hover:bg-neutral-500'>
                    <td class='text-white font-bold pl-12'><?php echo $partNumber ?>-M</td>
                    <td class='font-bold whitespace-nowrap text-center px-3 py-3'><?php echo round($avgPrice) ?></td>
                    <td class='whitespace-nowrap px-3 py-3 text-center border-black border-r-2'><?php echo round($avgPrice * 1.1) ?></td>
                    <?php
                    if (mysqli_num_rows($rates) > 0) {
                        while ($rate = mysqli_fetch_assoc($rates)) {
                    ?>
                            <td class="whitespace-nowrap px-3 py-3 text-center  b-<?php echo $rate['status'] ?>">
                                <?php echo  round($avgPrice * $rate['amount'] * 1.25 * 1.3) ?>
                            </td>
                    <?php
                        }
                    }
                    ?>
                    <td></td>
                    <td></td>
                </tr>
            <?php } ?>
            <?php
            if ($korea) {
                $price = $korea;
                $price = str_replace(",", "", $price);
                $avgPrice = round(($price * 110) / 243.5);
                $rates_sql = "SELECT * FROM rates ORDER BY amount ASC";
                $rates = mysqli_query($conn, $rates_sql);
            ?>

                <tr class='mobis transition duration-400 ease-in-out bg-amber-600'>
                    <td class='text-white font-bold pl-12'> <?php echo $partNumber ?>K</td>
                    <td class='font-bold whitespace-nowrap text-center px-3 py-3'><?php echo round($avgPrice) ?></td>
                    <td class='whitespace-nowrap px-3 py-3 text-center border-black border-r-2'><?php echo round($avgPrice * 1.1) ?></td>
                    <?php
                    if (mysqli_num_rows($rates) > 0) {
                        while ($rate = mysqli_fetch_assoc($rates)) {
                    ?>
                            <td class='whitespace-nowrap px-3 py-3 text-center  b-<?php echo $rate['status'] ?>'>
                                <?php echo  round($avgPrice * $rate['amount'] * 1.25 * 1.3) ?>
                            </td>
                    <?php
                        }
                    }
                    ?>
                    <td></td>
                    <td></td>
                </tr>
<?php }
        }
    }
} ?>