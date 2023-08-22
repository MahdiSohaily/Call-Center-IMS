<?php
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin

// Other headers to allow various types of requests
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
require_once 'vendor/autoload.php';
require_once './utilities/helper.php';

use danog\MadelineProto\API;

$MadelineProto = new API('bot.madeline');

define('BOT', $MadelineProto);
// Works OK with both bots and user bots
$MadelineProto->start();

if (filter_has_var(INPUT_POST, 'sendMessage')) {
    $receiver = $_POST['receiver'];
    $code = $_POST['code'];
    $price = $_POST['price'];
    $MadelineProto->messages->sendMessage(peer: $receiver, message: "$code : $price");
    echo "$receiver => $code";
} else {
    // Replace "GROUP_CHAT_ID" with the ID of the group chat you want to get messages from
    $groupChatId = 'https://t.me/+P83Wt7C1421iZDU0';

    $index_file = fopen('index.txt', 'r+');

    $message_index = fread($index_file, 1024);

    // Get the last read message ID from the file
    $lastReadMessageId = intval($message_index) ?? 0; // Convert to integer

    $messages = $MadelineProto->messages->getHistory([
        'peer' => $groupChatId,
        'limit' => 300, // You can change the limit to get more or fewer messages
        'min_id' => $lastReadMessageId, // Get messages newer than the last read message
    ]);

    $unreadMessages = [];

    // New associative array to store messages by name
    $messagesBySender = [];

    // Set the time zone to 'Asia/Tehran'
    date_default_timezone_set('Asia/Tehran');

    foreach ($messages['messages'] as $message) {
        // Check if the message is from a user (a person) and ignore system messages
        if (isset($message['from_id']) && $message['from_id'] > 0 && empty($message['action'])) {
            $senderInfo = $MadelineProto->getInfo($message['from_id']);
            $senderName = trim($senderInfo['User']['first_name']);
            $senderLastName = isset($senderInfo['User']['last_name']) ? trim($senderInfo['User']['last_name']) : '';
            $senderUsername = $senderInfo['User']['username'] ?? '';

            // Construct the full name
            $fullName = $senderName . ($senderLastName !== '' ? ' ' . $senderLastName : '');

            $messageTime = date('Y-m-d H:i:s', $message['date']); // Format the timestamp

            $unreadMessages[] = [
                'sender' => "$fullName ($senderUsername)",
                'message' => $message['message'],
                'message_time' => $messageTime,
                'message_id' => $message['id']
            ];

            // Check if the name already exists in the new array
            if (!isset($messagesBySender[$senderUsername])) {
                $messagesBySender[$senderUsername]['code'] = [];
                $messagesBySender[$senderUsername]['message'] = [];
                $messagesBySender[$senderUsername]['name'] = [];
                $messagesBySender[$senderUsername]['userName'] = [];
                $messagesBySender[$senderUsername]['profile'] = [];
            }


            // Append the message to the array under the name key
            array_push($messagesBySender[$senderUsername]['code'], filterCode($message['message']));
            array_push($messagesBySender[$senderUsername]['message'], $message['message']);
            array_push($messagesBySender[$senderUsername]['name'],  $fullName);
            array_push($messagesBySender[$senderUsername]['userName'],  $senderUsername);
            array_push($messagesBySender[$senderUsername]['profile'],  getProfilePicture($MadelineProto, $senderUsername));
        }
    }

    // Sort unread messages based on message ID in ascending order
    usort($unreadMessages, function ($a, $b) {
        return $a['message_id'] - $b['message_id'];
    });

    // Update the last read message ID in the cookie
    if (!empty($unreadMessages)) {
        $lastUnreadMessageId = end($unreadMessages)['message_id'];

        // Clear previous content and write new content
        ftruncate($index_file, 0); // Clear file content
        rewind($index_file); // Move pointer to the beginning of the file
        fwrite($index_file, $lastUnreadMessageId); // Write new content
    }

    fclose($index_file);
}

function getProfilePicture($MadelineProto, $username)
{
    try {
        $info = $MadelineProto->getPropicInfo($username) ?? false;

        $output_file_name = $MadelineProto->downloadToDir($info, './img/telegram');

        $url = explode('/', $output_file_name);

        return end($url);
    } catch (\Exception $e) {
        return 'images.png';
    }
}

echo json_encode($messagesBySender);
