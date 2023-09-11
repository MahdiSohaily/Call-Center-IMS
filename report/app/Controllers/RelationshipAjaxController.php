<?php
session_start();
require_once('../../database/connect.php');

if (isset($_POST['search_goods_for_relation'])) {
    $pattern = $_POST['pattern'];
    $sql = "SELECT * FROM yadakshop1402.nisha WHERE partnumber LIKE '" . $pattern . "%'";
    $result = mysqli_query($conn, $sql);

    $searched_ids = [];
    $nisha = [];

    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            array_push($searched_ids, $item['id']);
            array_push($nisha, $item);
        }
        $similar_sql = "SELECT nisha_id, pattern_id FROM similars WHERE nisha_id IN (" . implode(',', $searched_ids) . ")";

        $similar = mysqli_query($conn, $similar_sql);

        $similar_ids = [];

        if (mysqli_num_rows($similar) > 0) {
            while ($item = mysqli_fetch_assoc($similar)) {
                array_push($similar_ids, ['pattern_id' => $item['pattern_id'], 'nisha_id' => $item['nisha_id']]);
            }
        }

        $final_result = [];

        foreach ($nisha as $key => $value) {
            $id = $value['id'];

            $get_nisha = null;

            foreach ($similar_ids as $item) {
                if ($item['nisha_id'] == $id) {
                    $get_nisha = $item['pattern_id'];
                }
            }
            array_push($final_result, ['id' => $id, 'partNumber' => $value['partnumber'], 'pattern' => $get_nisha]);
        }




        if (count($final_result) > 0) {
            foreach ($final_result as $item) {
                if ($item['pattern']) { ?>
                    <div class="w-full flex justify-between items-center shadow-md hover:shadow-lg 
                        rounded-md px-4 py-3 mb-2 border-1 border-gray-300" id="search-<?php echo  $item['id'] ?>">
                        <p class=' text-sm font-semibold text-gray-600'><?php echo $item['partNumber'] ?></p>
                        <i data-id="<?php echo $item['id'] ?>" data-pattern="<?php echo $item['pattern'] ?>" data-partNumber="<?php echo $item['partNumber'] ?>" class='material-icons add text-blue-600 cursor-pointer rounded-circle hover:bg-gray-200' onclick="load(this)">cloud_download
                        </i>
                    </div>
                <?php
                } else {
                ?>
                    <div class='w-full flex justify-between items-center shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border-1 border-gray-300' id="search-<?php echo $item['id'] ?>">
                        <p class=' text-sm font-semibold text-gray-600'><?php echo $item['partNumber'] ?></p>
                        <i data-id="<?php echo $item['id'] ?>" data-partNumber="<?php echo $item['partNumber'] ?>" class="add_element material-icons add text-green-600 cursor-pointer rounded-circle hover:bg-gray-200" onclick="add(this)">add_circle_outline
                        </i>
                    </div>
                <?php
                }
                ?>
        <?php }
        }
    } else {
        ?>
        <div class='w-full text-center shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border-1 border-gray-300''>
                <i class=' material-icons text-red-500'>error</i>
            <br />
            <p class='text-sm font-semibold text-gray-600 text-red-500'>کد وارد شده در سیستم موجود نمی باشد</p>
        </div>
<?php

    }
}

if (isset($_POST['store_relation'])) {

    $relation_name = $_POST['relation_name'];
    $price = null;
    $cars = json_decode($_POST['cars']);
    $status = $_POST['status'];
    $original = $_POST['original'];
    $fake = $_POST['fake'];
    $description = $_POST['description'];
    $mode = $_POST['mode'];
    $pattern_id = $_POST['pattern_id'];
    $selected_goods = json_decode($_POST['selected_goods']);
    $serial = $_POST['serial'];

    if ($mode === 'create') {
        $selected_index = extract_id($selected_goods);

        $selectedCars = $cars;
        date_default_timezone_set("Asia/Tehran");
        $created_at = date("Y-m-d H:i:s");
        // create the pattern record
        $pattern_sql = "INSERT INTO patterns (name, price, serial, status_id, created_at, description)
            VALUES ('" . $relation_name . "', '" . $price . "', '" . $serial . "', '" . $status . "', '" . $created_at . "','" . $description . "')";

        if ($conn->query($pattern_sql) === TRUE) {
            $last_id = $conn->insert_id;

            $limit_sql = $conn->prepare("INSERT INTO good_limit (nisha_id, original, fake, user_id) VALUES (?, ?, ?, ?)");
            $similar_sql = $conn->prepare("INSERT INTO similars (pattern_id, nisha_id) VALUES (? , ?)");

            foreach ($selected_index as $value) {
                $nisha_id = intval($value);

                $limit_sql->bind_param('iiii', $nisha_id, $original, $fake, $_SESSION['user_id']);
                $limit_sql->execute();

                $similar_sql->bind_param('ii', $last_id, $nisha_id);
                $similar_sql->execute();
            }

            $car_sql = $conn->prepare("INSERT INTO patterncars (pattern_id, car_id) VALUES (?, ?)");
            foreach ($selectedCars as $car) {
                $car_id = intval($car);
                $car_sql->bind_param('ii', $last_id, $car_id);
                $car_sql->execute();
            }
            echo 'true';
        } else {
            echo 'false';
        }
    }

    if ($mode === 'update') {

        $pattern_sql = "SELECT *  FROM patterns WHERE id ='" . $pattern_id . "'";
        $is_exist = $conn->query($pattern_sql);

        if ($is_exist) {
            $similar_sql = "SELECT nisha_id  FROM similars WHERE pattern_id ='" . $pattern_id . "'";
            $all_simillers = $conn->query($similar_sql);

            $selected_index = extract_id($selected_goods);

            $current = [];
            if (mysqli_num_rows($all_simillers) > 0) {
                while ($item = mysqli_fetch_assoc($all_simillers)) {
                    array_push($current, $item['nisha_id']);
                }
            }

            $cars_sql = "SELECT car_id  FROM patterncars WHERE pattern_id ='" . $pattern_id . "'";
            $all_cars = $conn->query($cars_sql);

            $current_cars = [];
            if (mysqli_num_rows($all_cars) > 0) {
                while ($item = mysqli_fetch_assoc($all_cars)) {
                    array_push($current_cars, $item['car_id']);
                }
            }

            $toAdd = toBeAdded($current, $selected_index);
            $toDelete = toBeDeleted($current, $selected_index);


            $subtractNew = array_diff($current, $toAdd);
            $updateRemaining = array_diff($subtractNew, $toDelete);


            $selectedCars =  $cars;
            $carsToAdd = toBeAdded($current_cars, $selectedCars);
            $carsToDelete = toBeDeleted($current_cars, $selectedCars);
            date_default_timezone_set("Asia/Tehran");
            $created_at = date("Y-m-d H:i:s");

            $update_pattern_sql = "UPDATE patterns SET name= '" . $relation_name . "', price = '" . $price . "',
                serial = '" . $serial . "' , status_id =  '" . $status . "', created_at = '" . $created_at . "', description = '" . $description . "'   WHERE id = '$pattern_id'";
            $conn->query($update_pattern_sql);

            if (count($toAdd) > 0) {
                $limit_sql = $conn->prepare("INSERT INTO good_limit (nisha_id, original, fake, user_id) VALUES (?, ?, ?, ?)");

                foreach ($toAdd as $value) {
                    $nisha_id = intval($value);
                    $limit_sql->bind_param('iiii', $nisha_id, $original, $fake, $_SESSION['user_id']);
                    $limit_sql->execute();

                    $similar_sql = "INSERT INTO similars (pattern_id, nisha_id) VALUES ('" . $pattern_id . "', '" . $value . "')";
                    $conn->query($similar_sql);
                }
            }
            if (count($toDelete)) {
                $limit_sql = $conn->prepare("DELETE FROM good_limit WHERE nisha_id = ?");

                foreach ($toDelete as $value) {
                    $nisha_id = intval($value);
                    $limit_sql->bind_param('i', $nisha_id);
                    $limit_sql->execute();

                    $delete_similar_sql = "DELETE FROM similars WHERE nisha_id= '" . $value . "'";
                    $conn->query($delete_similar_sql);
                }
            }

            if (sizeof($updateRemaining)) {
                $limit_sql = $conn->prepare("UPDATE good_limit SET original= ?,  fake = ?,  user_id= ? WHERE nisha_id = ?");

                foreach ($updateRemaining as $item) {
                    $nisha_id = intval($item);
                    $limit_sql->bind_param('iiii', $original, $fake, $_SESSION['user_id'], $nisha_id);
                    $limit_sql->execute();
                }
            }

            if (count($carsToAdd) > 0) {
                foreach ($carsToAdd as $value) {
                    $cars_sql = "INSERT INTO patterncars (pattern_id, car_id) VALUES ('" . $pattern_id . "', '" . $value . "')";
                    $conn->query($cars_sql);
                }
            }
            if (count($carsToDelete)) {
                foreach ($carsToDelete as $value) {
                    $delete_cars_sql = "DELETE FROM patterncars WHERE car_id= '" . $value . "'";
                    $conn->query($delete_cars_sql);
                }
            }
            echo 'true';
        } else {
            echo 'false';
        }
    }
}

if (isset($_POST['load_relation'])) {
    $pattern = $_POST['pattern'];
    $similar_sql = "SELECT nisha_id FROM similars WHERE pattern_id='" . $pattern . "'";
    $result = mysqli_query($conn, $similar_sql);

    $final_result = [];
    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            $nisha_sql = "SELECT id, partnumber FROM yadakshop1402.nisha WHERE id='" . $item['nisha_id'] . "'";
            $nisha = mysqli_query($conn, $nisha_sql);
            $data = mysqli_fetch_assoc($nisha);

            array_push($final_result, ['id' =>  $data['id'], 'partNumber' => $data['partnumber'], 'pattern' => $item['nisha_id']]);
        }

        $getLimit = current($final_result)['pattern'];

        $limit_sql = "SELECT original, fake FROM good_limit WHERE nisha_id = '" . $getLimit . "'";
        $limit = $conn->query($limit_sql);
        $limit = $limit->fetch_assoc();

        print_r(json_encode([$limit, $final_result]));
    }
}

if (isset($_POST['load_pattern_ifo'])) {

    $pattern = $_POST['pattern'];
    $car_sql = "SELECT car_id FROM patterncars WHERE pattern_id='" . $pattern . "'";
    $result = mysqli_query($conn, $car_sql);

    $cars_id = [];
    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            array_push($cars_id, $item['car_id']);
        }
    }

    $pattern_sql = "SELECT * FROM patterns WHERE id='" . $pattern . "'";
    $pattern_result = mysqli_query($conn, $pattern_sql);

    $pattern_info =  mysqli_fetch_assoc($pattern_result);

    print_r(json_encode(['pattern' => $pattern_info, 'cars' => $cars_id]));
}

function extract_id($array)
{
    $selected_index = [];
    foreach ($array as $value) {
        array_push($selected_index, $value->id);
    }
    $selected_index = array_unique($selected_index);
    return $selected_index;
}

function toBeAdded($existing, $newComer)
{
    $result = [];
    foreach ($newComer as $item) {
        if (!in_array($item, $existing)) {
            array_push($result, $item);
        }
    }
    return $result;
}
function toBeDeleted($existing, $newComer)
{
    $result = [];
    foreach ($existing as $item) {
        if (!in_array($item, $newComer)) {
            array_push($result, $item);
        }
    }
    return $result;
}
