<?php
session_start();
require_once 'vendor/autoload.php';

$MadelineProto = new \danog\MadelineProto\API('bot.madeline');

// Works OK with both bots and userbots
$MadelineProto->start();

// Replace "GROUP_CHAT_ID" with the ID of the group chat you want to get unread messages from
$groupChatId = '-907362775';

$lastReadMessageId = isset($_SESSION['lastReadMessageId']) ? $_SESSION['lastReadMessageId'] : 0;
$minMessageId = $lastReadMessageId;

$messages = $MadelineProto->messages->getHistory([
    'peer' => $groupChatId,
    'limit' => 300,
    'min_id' => $minMessageId - 1,
]);


//print_r(json_encode($messages['messages']));
usort($messages['messages'], 'compareById');


function compareById($a, $b)
{
    return   $a['id'] - $b['id'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Chat Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://via.placeholder.com/1200x800') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">
    <!-- Chat container -->
    <div class="bg-white w-full  mx-auto rounded-lg shadow-lg p-4 w-10">
        <!-- Chat messages -->
        <div class="flex flex-col space-y-4 max-h-80 overflow-y-auto">
            <?php
            $complete = '';
            $users = [];
            foreach ($messages['messages'] as $message) :
                // Check if the message is from a user (a person) and ignore system messages
                if (isset($message['from_id']) && $message['from_id'] > 0 && empty($message['action'])) :
                    $senderInfo = $MadelineProto->getInfo($message['from_id']);
                    $senderName = trim($senderInfo['User']['first_name']);
                    $senderLastName = isset($senderInfo['User']['last_name']) ? trim($senderInfo['User']['last_name']) : '';
                    $senderUsername = $senderInfo['User']['username'] ?? '';

                    // Construct the full name
                    $fullName = $senderName . ($senderLastName !== '' ? ' ' . $senderLastName : '');

                    $complete .= $message['message'];

                    array_push($users, $senderUsername);
                    if ($senderUsername === 'AfgDeveloper') :
            ?>

                        <!-- Message 1 (received) -->
                        <div class="flex items-start">
                            <img src="https://via.placeholder.com/40" alt="User Avatar" class="w-10 h-10 rounded-full">
                            <div class="bg-gray-500 text-white w-96 px-4 py-2 rounded-lg shadow-md">
                                <p><?php echo $message['id'] . ": $fullName ($senderUsername)"; ?></p>
                                <p><?php echo nl2br($message['message']) ?></p>
                            </div>
                        </div>
                    <?php else : ?>
                        <!-- Message 2 (sent) -->
                        <div class="flex items-end justify-end">
                            <div class="bg-blue-500 text-white w-96 px-4 py-2 rounded-lg shadow-md">
                                <p><?php echo $message['id'] . ": $fullName ($senderUsername)"; ?></p>
                                <p><?php echo $message['message'] ?></p>
                            </div>
                            <img src="https://via.placeholder.com/40" alt="User Avatar" class="w-10 h-10 rounded-full ml-3">
                        </div>

                        <!-- Add more messages here -->

            <?php
                    endif;
                endif;
                $_SESSION['lastReadMessageId'] = $message['id'];
            endforeach;

            function filterCode($elementValue)
            {
                if (empty($elementValue)) return;

                $codes = explode("\n", $elementValue);
                $filteredCodes = array_map(function ($code) {
                    $removedText = preg_replace('/\[[^\]]*\]/', '', $code);
                    $parts = strpos($removedText, ':') !== false ? explode(':', $removedText) : explode(',', $removedText);
                    $rightSide = !empty($parts[1]) ? trim(preg_replace('/[^a-zA-Z0-9 ]/', '', $parts[1])) : '';
                    return !empty($rightSide) ? $rightSide : preg_replace('/[^a-zA-Z0-9 ]/', '', $removedText);
                }, array_filter($codes, function ($code) {
                    return strlen(trim($code)) > 0;
                }));

                $finalCodes = array_filter($filteredCodes, function ($item) {
                    return strlen(explode(' ', $item)[0]) > 6;
                });

                $finalCodes = array_map(function ($item) {
                    return explode(' ', $item)[0];
                }, $finalCodes);

                $finalCodes = array_filter($finalCodes, function ($item) {
                    $consecutiveChars = preg_match('/[a-zA-Z]{4,}/', $item);
                    return !$consecutiveChars;
                });

                return implode("\n", $finalCodes) . "\n"; // Changed single quotes to double quotes here
            }

            ?>
        </div>
    </div>

    <!-- Input area -->
    <div class="fixed bottom-0 left-0 right-0 bg-white px-4 py-3 shadow-md" style="height: 400px;">
        <form action="../YadakShop-APP/callcenter/report/giveOrderedPrice.php" method="post" class="min-w-full bg-green-300">
            <input type="text" name="givenPrice" value="givenPrice" id="form" hidden>
            <input type="text" name="user" value="1" hidden>
            <input type="text" name="customer" value="1" id="target_customer" hidden>
            <textarea name="code" style="height: 300px; width:600px" class="flex-1 px-3 py-2 border rounded-lg
             resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo filterCode($complete); ?></textarea>
            <br>
            <button type="submit" name="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg ml-3">Send</button>
        </form>

    </div>
</body>

</html>
<?php
if (filter_has_var(INPUT_POST, 'submit')) {
    $MadelineProto->messages->sendMessage(peer: -907362775, message: $_POST['code']);
}

$users = array_unique($users);

// Function to generate a random Persian-style "poem" using Lorem Ipsum text
function generateRandomPersianPoem()
{
    $loremIpsumText = <<<EOT
شب‌های تاریک و بی‌تاب
به آرامش پایان نمی‌یابد
عشق در دل تپانچه‌ها می‌خوابد
و آه از لبان فریاد می‌آید

پرستوهای گم‌شده
در آغوش افکار سرگردان
به دام دنیایی تاریک افتاده‌اند
و بال‌زده‌اند بر سر گندمی‌دیگر

دست‌های خسته و بی‌امید
در آغوش چمن خراب می‌خوابند
و خورشید آرامش خویش را
به چشمان خیس اشک می‌پوشاند

آن‌ها که باور به جادو دارند
در هر زمان می‌خواهند برکنار شوند
اما پابرجا می‌مانند
در این جادوی تاریک

راهی ندارد تا نجات
چرا که هیچ‌کس نمی‌داند کجاست
و گمشده‌اند همگی
در این شب‌های تاریک

بی‌تاب و بی‌خواب
به سرنوشت بخت بسته‌اند
چه کنیم که تمام شد
عشق‌هایی که تا روز گذشته برکنار کرده‌ایم
اما باز هم نیازمندیم به جادو

با امید به روزی دیگر
که عشق بازگردد به دل‌ها
و رویاها برآورده شوند
به پابرجا ماندن در این جادوی تاریک
EOT;

    return $loremIpsumText;
}

// Assuming $users is an array containing user IDs or other relevant information
foreach ($users as $user) {
    $poem = generateRandomPersianPoem();
    // Assuming $MadelineProto is already initialized and authenticated
    
    // Define the user ID for which you want to display the profile
    $user_id = 123456; // Replace this with the actual user ID you want to display the profile for
    
    try {
        // Get detailed information about the user
        $userInfo = $MadelineProto->get_info($user_id);
        
        // Get user's first name
        $firstName = $userInfo['User']['first_name'];
        
        // Get user's last name (if available)
        $lastName = isset($userInfo['User']['last_name']) ? $userInfo['User']['last_name'] : '';
        
        // Get user's username (if available)
        $username = $userInfo['User']['username'] ?? '';
        
        // Get user's profile photos
        $profilePhotos = $MadelineProto->photos->getUserPhotos(['user_id' => $user_id, 'offset' => 0, 'limit' => 1]);
        $profilePhotoUrl = '';
    
        if ($profilePhotos['photos'] && count($profilePhotos['photos']) > 0) {
            $profilePhoto = $profilePhotos['photos'][0];
            $profilePhotoFile = $MadelineProto->download_to_dir($profilePhoto, sys_get_temp_dir());
            $profilePhotoUrl = $profilePhotoFile;
        } else {
            // If there are no profile photos available, you can display a default image
            $profilePhotoUrl = "path_to_default_image.jpg"; // Replace this with the path to your default image
        }
    } catch (\danog\MadelineProto\RPCErrorException $e) {
        // Handle RPC errors if needed
        echo "Error: " . $e->getMessage();
        exit;
    }
    ?>
    
    <!-- Display the user's profile information -->
    <div>
        <?php if ($profilePhotoUrl) { ?>
            <img src="<?php echo $profilePhotoUrl; ?>" alt="User Avatar" class="w-10 h-10 rounded-full">
        <?php } else { ?>
            <img src="https://via.placeholder.com/40" alt="User Avatar" class="w-10 h-10 rounded-full">
        <?php } ?>
        <p>First Name: <?php echo $firstName; ?></p>
        <?php if ($lastName !== '') { ?>
            <p>Last Name: <?php echo $lastName; ?></p>
        <?php } ?>
        <?php if ($username !== '') { ?>
            <p>Username: <?php echo $username; ?></p>
        <?php } ?>
    </div>
    
?>

    Display the user's profile photo
    <img src="<?php echo $profilePhotoPath; ?>" alt="User Avatar" class="w-10 h-10 rounded-full">

<?php
}


?>