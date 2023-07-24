<?php
require_once './php/function.php';
require_once './config/database.php';
require_once './php/jdf.php';

if (filter_has_var(INPUT_POST, 'operation')) :
    $toBeDelete = filter_input(INPUT_POST, 'toBeDelete', FILTER_SANITIZE_NUMBER_INT);
    $sql = "DELETE FROM estelam WHERE id = $toBeDelete";
    if (mysqli_query($conn, $sql) === TRUE) {
        echo true;
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
endif;
