<?php
$applyDate = "2023-11-02 20:52:41";
$additionRate = 2;
$rateSpecification  = getDollarRateInfo();

if ($rateSpecification) {
    $applyDate = $rateSpecification['created_at'];
    $additionRate = $rateSpecification['rate'];
}
function getDollarRateInfo()
{
    $statement = "SELECT * FROM shop.dollarrate WHERE status = 1";
    $result = CONN->query($statement);
    $rate = $result->fetch_assoc();
    return $rate;
}

// helper functions function filterCode($elementValue)
function filterCode($message)
{
    if (empty($message)) {
        return '';
    }

    $codes = explode("\n", $message);

    $filteredCodes = array_map(function ($code) {
        $code = preg_replace('/\[[^\]]*\]/', '', $code);
        $parts = preg_split('/[:,]/', $code, 2);
        $rightSide = trim(preg_replace('/[^a-zA-Z0-9 ]/', '', $parts[1] ?? ''));
        return !empty($rightSide) ? $rightSide : trim(preg_replace('/[^a-zA-Z0-9 ]/', '', $code));
    }, array_filter($codes, 'trim'));

    $finalCodes = array_filter($filteredCodes, function ($item) {
        $data = explode(" ", $item);
        if (strlen($data[0]) > 4) {
            return $item;
        }
    });

    $finalCodes = array_map(function ($item) {
        $item = explode(' ', $item);
        if (count($item) >= 2) {
            $partOne = $item[0];
            $partTwo = $item[1];
            if (!preg_match('/[a-zA-Z]{4,}/i', $partOne) && !preg_match('/[a-zA-Z]{4,}/i', $partTwo)) {
                return $partOne . $partTwo;
            }
        }
        return $item[0];
    }, $finalCodes);

    $finalCodes = array_filter($finalCodes, function ($item) {
        $consecutiveChars = preg_match('/[a-zA-Z]{4,}/i', $item);
        return !$consecutiveChars;
    });

    return implode("\n", array_map(function ($item) {
        return explode(' ', $item)[0];
    }, $finalCodes)) . "\n";
}

function displayTimePassed($datetimeString)
{
    if ($datetimeString) {
        $date_parts = explode('/', $datetimeString);
        $datetimeString = jalali_to_gregorian(abs($date_parts[0]), abs($date_parts[1]), abs($date_parts[2]));
        $month_days_num = [30, 29, 31, 31, 31, 31, 31, 31, 30, 30, 30, 30];
        date_default_timezone_set('Asia/Tehran');
        $datetime = new DateTime(join('-', $datetimeString));
        $month = $datetime->format("m");
        $now = new DateTime();

        $interval = $now->diff($datetime);

        $totalDays = $interval->days;

        $passedYears = floor($totalDays / 365);
        $remainingDays = $totalDays % 365;

        $passedMonths = floor($remainingDays / $month_days_num[$month - 1]);
        $passedDays = $remainingDays % $month_days_num[$month - 1];

        $persianYears = convertToPersian($passedYears);
        $persianMonths = convertToPersian($passedMonths);
        $persianDays = convertToPersian($passedDays);

        $result = "";

        if ($passedYears > 0) {
            $result .= "$persianYears سال";
        }

        if ($passedMonths > 0) {
            if ($passedYears > 0) {
                $result .= " و ";
            }
            $result .= "$persianMonths ماه";
        }

        if ($passedDays > 0) {
            if ($passedYears > 0 || $passedMonths > 0) {
                $result .= " و ";
            }
            $result .= "$persianDays روز";
        }
        return $result;
    }

    return 'تاریخ ورود موجود نیست';
}


function convertToPersian($number)
{
    $persianDigits = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $persianNumber = '';

    while ($number > 0) {
        $digit = $number % 10;
        $persianNumber = $persianDigits[$digit] . $persianNumber;
        $number = (int)($number / 10);
    }

    return $persianNumber;
}

function applyDollarRate($price)
{
    // Define a regular expression pattern to match numbers with optional forward slashes
    $pattern = '/(\d+(?:\/\d+)?)/';

    // Use preg_replace_callback to modify each matched number
    $modifiedString = preg_replace_callback($pattern, function ($matches) {
        // Extract the matched number, removing any forward slashes
        $number = str_replace('/', '', $matches[1]);

        // Increase the matched number by 2%
        $modifiedNumber = $number + (($number *  $GLOBALS['additionRate']) / 100); // Increase by 2%

        // Round the number to the nearest multiple of 10
        $roundedNumber = round($modifiedNumber / 10) * 10;

        return $roundedNumber;
    }, $price);

    return $modifiedString;
}

function checkDateIfOkay($applyDate, $priceDate)
{
    // Extract only the date part from the datetime strings
    $applyDate = date('Y-m-d', strtotime($applyDate));
    $priceDate = date('Y-m-d', strtotime($priceDate));

    return $priceDate <= $applyDate;
}
