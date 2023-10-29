<?php
require_once('../../database/connect.php');

// Check is the request is valid and submitted operation type
if (isset($_POST['operation']) and $_POST['operation'] == 'update') :

    try {
        $user = $_POST['user'] ?? 0;
        $data = $_POST['data'] ?? null;

        updateUserAuthorityList($user, $data);
    } catch (\Throwable $th) {
        return $th;
    }


endif;


function updateUserAuthorityList($id, $data)
{
    $stmt = CONN->prepare("UPDATE yadakshop1402.authorities SET user_authorities= ? , modified = 1  WHERE user_id = ?");
    $stmt->bind_param('si', $data, $id);
    $stmt->execute();
    return true;
}
 