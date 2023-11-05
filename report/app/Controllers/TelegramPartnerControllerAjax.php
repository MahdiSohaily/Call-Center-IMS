<?php
require_once('../../database/connect.php');


if (isset($_POST['operation'])) {

    $chat_id = $_POST['chat_id'];
    $name = $_POST["name"];
    $username = $_POST["username"];
    $profile = $_POST["profile"];
    $data = json_decode($_POST['data'], true);

    $honda = $data['honda'];
    $kia = $data['kia'];
    $chines = $data['chines'];

    if ($_POST['operation'] == 'update') {
        if ($honda == null && $kia == null && $chines == null) {
            $sql = "DELETE FROM shop.telegram_partner WHERE chat_id = '$chat_id'";
            CONN->query($sql);
        } else {
            updatePartner($chat_id, $data);
        }
    } else {
        if (partnerExist($chat_id)) {
            if ($honda == null && $kia == null && $chines == null) {
                $sql = "DELETE FROM shop.telegram_partner WHERE chat_id = '$chat_id'";
                CONN->query($sql);
            }
            updatePartner($chat_id, $data);
        } else {
            createPartner($chat_id, $name, $username, $profile, $data);
        }
    }
}


if (isset($_POST['getCategories'])) {
    $categories = json_decode($_POST['data'], true);
    // Use array_filter to keep only items with a value of 1
    $result = array_filter($categories, function ($value) {
        return $value == 1;
    });

    $users_Group = array();
    foreach ($result as $key => $value) {
        $users_Group[$key] = getPartners($key);
    }
    // Use array_filter with a callback to filter out arrays with empty values
    $filteredUsers = array_filter($users_Group, function ($user) {
        return array_reduce($user, function ($carry, $value) {
            return $carry || !empty($value);
        }, false);
    });

    // Send the JSON response with appropriate headers
    header('Content-Type: application/json');
    echo json_encode($filteredUsers);
}

if (isset($_POST['logAction'])) {
    $log_info = $_POST;

    // Convert the data to a string
    $log_data = json_encode($log_info);

    // Define the file path
    $log_file = 'telegram_partner_log.txt';

    // Open the file in write mode (create if it doesn't exist)
    $file_handle = fopen($log_file, 'a'); // 'a' for append

    if ($file_handle !== false) {
        // Write the data to the file
        fwrite($file_handle, $log_data . "\n");

        // Close the file
        fclose($file_handle);
    } else {
        // Handle file open error
        echo 'Error opening log file';
    }
}

if (isset($_POST['getInitialData'])) {
    echo json_encode(getExistingTelegramPartners());
}

if (isset($_POST['getExistingCategories'])) {
    echo json_encode(getCategories());
}

if (isset($_POST['editCategory'])) {

    $id = $_POST['id'];
    $value = $_POST['value'];

    editCategory($id, $value);
}

if (isset($_POST['createCategory'])) {
    $value = $_POST['value'];

    createCategory($value);
}
if (isset($_POST['delete_category'])) {
    $id = $_POST['id'];
    deleteCategory($id);
}

function getCategories()
{
    $sql = "SELECT * FROM shop.partner_categories";

    $result = CONN->query($sql);

    // Initialize an array to store all the rows
    $data = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->close();
    }

    return $data;
}
function getPartners($key)
{
    $sql = "SELECT name, chat_id FROM shop.telegram_partner WHERE $key = 1";

    $result = CONN->query($sql);

    $data = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->close();
    }

    return $data;
}

function partnerExist($id)
{
    $sql = "SELECT * FROM shop.telegram_partner WHERE chat_id = '$id'";
    $result = CONN->query($sql);

    $data = $result->fetch_assoc();
    return !empty($data);
}

function updatePartner($chat_id, $data)
{
    $honda = $data['honda'];
    $kia = $data['kia'];
    $chines = $data['chines'];

    $sql = "UPDATE shop.telegram_partner SET honda = '$honda', kia = '$kia', chines = '$chines' WHERE chat_id = '$chat_id'";

    CONN->query($sql);
}

function createPartner($chat_id, $name, $username, $profile, $data)
{
    $honda = $data['honda'];
    $kia = $data['kia'];
    $chines = $data['chines'];

    $sql = "INSERT INTO shop.telegram_partner (chat_id, name, username, profile, honda, kia, chines) 
    VALUES ('$chat_id', '$name', '$username', '$profile', '$honda', '$kia', '$chines')";

    CONN->query($sql);
}

function getExistingTelegramPartners()
{
    $sql = "SELECT * FROM shop.telegram_partner";

    $result = CONN->query($sql);

    // Initialize an array to store all the rows
    $data = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->close();
    }

    return $data;
}


function editCategory($id, $value)
{
    $sql = "UPDATE shop.partner_categories SET name= '$value' WHERE id = '$id'";

    $result = CONN->query($sql);

    return $result;
}
function createCategory($value)
{
    $sql = "INSERT INTO shop.partner_categories (name) VALUES ('$value')";

    $result = CONN->query($sql);

    return $result;
}


function deleteCategory($id)
{
    $sql = "DELETE FROM shop.partner_categories WHERE id = '$id'";

    $result = CONN->query($sql);

    return $result;
}
