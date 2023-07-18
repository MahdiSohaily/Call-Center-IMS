<?php
$givenPrice = givenPrice($conn);
function givenPrice($conn)
{
    $sql = "SELECT 
        prices.price, prices.partnumber, users.username,customer.id AS customerID, users.id as userID, prices.created_at, customer.name, customer.family,
        customer.phone
        FROM ((shop.prices 
        INNER JOIN callcenter.customer ON customer.id = prices.customer_id )
        INNER JOIN yadakshop1402.users ON users.id = prices.user_id)
        ORDER BY prices.created_at DESC LIMIT 600";
    $result = mysqli_query($conn, $sql);


    $givenPrices = [];
    if (mysqli_num_rows($result) > 0) {
        while ($item = mysqli_fetch_assoc($result)) {
            array_push($givenPrices, $item);
        }
    }
    return  $givenPrices;
}
