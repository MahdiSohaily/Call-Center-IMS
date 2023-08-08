<?php
session_start();
require_once 'vendor/autoload.php';

$MadelineProto = new \danog\MadelineProto\API('bot.madeline');

// Works OK with both bots and user bots
$MadelineProto->start();

// Replace "GROUP_CHAT_ID" with the ID of the group chat you want to get unread messages from
$groupChatId = '-907362775';

// Getting the the index of the group chat you want to get unread messages from group
$lastReadMessageId = isset($_SESSION['lastReadMessageId']) ? $_SESSION['lastReadMessageId'] : 0;

$lastReadMessageId;

$messages = $MadelineProto->messages->getHistory([
    'peer' => $groupChatId,
    'limit' => 300,
    'min_id' => $lastReadMessageId,
]);


//Sorting the messages to display latest messages at the top
usort($messages['messages'], 'compareById');
