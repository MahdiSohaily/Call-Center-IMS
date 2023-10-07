<?php
require_once('../../database/connect.php');

// Check is the request is valid and submitted operation type
if (isset($_POST['operation']) and $_POST['operation'] == 'update') :

    try {
        $user = $_POST['user'] ?? 0;
        $authority = $_POST['authority'] ?? null;
        $isChecked = $_POST['isChecked'];
        print_r(json_encode([
            'usersManagement' => true,
            'khorojkala-index' => true,
            'vorodkala-index' => true,
        ]));
        echo getUserAuthorityList($user);
    } catch (\Throwable $th) {
        return $th;
    }


endif;


function getUserAuthorityList($id)
{
    $stmt = CONN->prepare("SELECT * FROM yadakshop1402.authorities WHERE user_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $user = $result->fetch_assoc();
    return $user['user_authorities'];
}
