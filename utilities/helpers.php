<?php
function getFirstLetters($string)
{
    // Trim the string and remove special characters
    $string = trim(preg_replace('/[^a-zA-Z0-9\sآ-ی]/u', '', $string));

    $words = preg_split('/\s+/u', $string);
    $firstLetters = '';

    if (count($words) === 1) {
        $firstLetters = mb_substr($words[0], 0, 2);
    } else {
        foreach ($words as $word) {
            $firstLetters .= mb_substr($word, 0, 1) . ' ';
        }
    }

    return trim($firstLetters);
}

function givenPrice($con)
{
    $sql = "SELECT 
prices.price, prices.partnumber, users.username,customer.id AS customerID, users.id as userID, prices.created_at, customer.name, customer.family
FROM ((shop.prices 
INNER JOIN callcenter.customer ON customer.id = prices.customer_id )
INNER JOIN yadakshop1402.users ON users.id = prices.user_id)
ORDER BY prices.created_at DESC LIMIT 40";
    $result = mysqli_query($con, $sql);


    $givenPrices = [];
    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            array_push($givenPrices, $item);
        }
    }
    return  $givenPrices;
}
