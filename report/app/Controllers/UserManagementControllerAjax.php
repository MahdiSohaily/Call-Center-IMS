<?php
require_once('../../database/connect.php');

// Check is the request is valid and submitted operation type
if (isset($_POST['operation']) and $_POST['operation'] == 'update') :

    try {
        $user = $_POST['user'] ?? 0;
        $authority = $_POST['authority'] ?? null;
        $isChecked = $_POST['isChecked'];
    } catch (\Throwable $th) {
        return false;
    }
endif;