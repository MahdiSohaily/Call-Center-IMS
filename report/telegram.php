<?php
require_once 'vendor/autoload.php';
require_once './utilities/helper.php';

use danog\MadelineProto\API;

$MadelineProto = new API('bot.madeline');

define('BOT', $MadelineProto);
// Works OK with both bots and userbots
$MadelineProto->start();

// Replace "GROUP_CHAT_ID" with the ID of the group chat you want to get messages from
$groupChatId = '-907362775';

// Get the last read message ID from the cookie
$lastReadMessageId = $_COOKIE['last_read_message_id'] ?? 0;


if (filter_has_var(INPUT_POST, 'sendMessage')) {

    $receiver = $_POST['receiver'];
    $code = $_POST['code'];
    $price = $_POST['price'];

    $MadelineProto->messages->sendMessage(peer: $receiver, message: "$code : $price");
} else {
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
                $messagesBySender[$senderUsername] = [];
            }

            // Append the message to the array under the name key
            $messagesBySender[$senderUsername][] =  filterCode($message['message']);
        }
    }

    // Sort unread messages based on message ID in ascending order
    usort($unreadMessages, function ($a, $b) {
        return $a['message_id'] - $b['message_id'];
    });

    // Update the last read message ID in the cookie
    if (!empty($unreadMessages)) {
        $lastUnreadMessageId = end($unreadMessages)['message_id'];
        setcookie('last_read_message_id', $lastUnreadMessageId, time() + 86400); // Store for one day
    }
}
