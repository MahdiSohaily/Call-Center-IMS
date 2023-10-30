<?php
// Initialize the session
session_name("MyAppSession");
session_start();

// Check if the user is already logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["not_allowed"])) {
    // Check if the session has expired (current time > expiration time)
    if ((isset($_SESSION["expiration_time"]) && time() > $_SESSION["expiration_time"]) || authModified(dbconnect(), $_SESSION['id'])) {
        // Session has expired, destroy it and log the user out
        session_unset();
        session_destroy();
        header("location: ../1402/login.php"); // Redirect to the login page
        exit;
    }
} else {
    // User is not logged in, redirect them to the login page
    header("location: ../1402/login.php");
    exit;
}

$current_page = explode(".", basename($_SERVER['PHP_SELF']))[0];

if (in_array($current_page, $_SESSION['not_allowed'])) {
    header("location: notAllowed.php"); // Redirect to the login page  header("location: login.php"); // Redirect to the login page
}

function authModified($con, $id)
{
    $sql = "SELECT modified FROM yadakshop1402.authorities WHERE user_id = $id";

    $result = $con->query($sql);

    $isModified = $result->fetch_assoc()['modified'];

    return $isModified;
}



function dbconnect()
{

    $con = mysqli_connect('localhost', 'root', '', 'callcenter');

    if (!$con) {
        die('Could not connect: ' . mysqli_error($con));
    }

    return $con;
}



function dbconnect2()
{

    $con = mysqli_connect('localhost', 'root', '', 'yadakshop1402');

    if (!$con) {
        die('Could not connect: ' . mysqli_error($con));
    }

    return $con;
}


function getip($x)
{



    $sql = "SELECT * FROM users WHERE id='$x'";
    $result = mysqli_query(dbconnect2(), $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $ip = $row['ip'];

            return $ip;
        }
    }
}

function getinternal($x)
{



    $sql = "SELECT * FROM users WHERE id='$x'";
    $result = mysqli_query(dbconnect2(), $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $internal = $row['internal'];

            return $internal;
        }
    }
}
function getnamebyinternal($x)
{



    $sql = "SELECT * FROM users WHERE internal='$x'";
    $result = mysqli_query(dbconnect2(), $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $name = $row['name'];
            $family = $row['family'];

            return $name . " " . $family;
        }
    }
}
function getfamilybyid($x)
{



    $sql = "SELECT * FROM users WHERE id='$x'";
    $result = mysqli_query(dbconnect2(), $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $family = $row['family'];

            return $family;
        }
    }
}
function getidbyinternal($x)
{



    $sql = "SELECT * FROM users WHERE internal='$x'";
    $result = mysqli_query(dbconnect2(), $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];

            return $id;
        }
    }
}
function nishatimedef($x, $y)
{


    date_default_timezone_set('Asia/Tehran');

    $datetime1 = new DateTime($x);
    $datetime2 = new DateTime($y);
    $interval = $datetime1->diff($datetime2);
    return $interval;
}

function format_interval(DateInterval $interval)
{
    $result = "";
    if ($interval->y) {
        $result .= $interval->format("%y سال ");
    }
    if ($interval->m) {
        $result .= $interval->format("%m ماه ");
    }
    if ($interval->d) {
        $result .= $interval->format("%d روز ");
    }
    if ($interval->h) {
        $result .= $interval->format("%h ساعت ");
    }
    if ($interval->i) {
        $result .= $interval->format("%i دقیقه ");
    }
    if ($interval->s) {
        $result .= $interval->format("%s ثانیه ");
    }
    $result .= "قبل";
    return $result;
}
function format_calling_time(DateInterval $interval)
{
    $result = "";
    if ($interval->y) {
        $result .= $interval->format("%y سال ");
    }
    if ($interval->m) {
        $result .= $interval->format("%m ماه ");
    }
    if ($interval->d) {
        $result .= $interval->format("%d روز ");
    }
    if ($interval->h) {
        $result .= $interval->format("%h ساعت ");
    }
    if ($interval->i) {
        $result .= $interval->format("%i دقیقه ");
    }
    if ($interval->s) {
        $result .= $interval->format("%s ثانیه ");
    }
    $result .= "";
    return $result;
}





function taglabellist()
{
    $sql = "SELECT * FROM label";
    $result = mysqli_query(dbconnect(), $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $name = $row['name'];
            $class = $row['class'];
            echo "<option value='" . $id . "' class='$class'>" . $name . "</option>";
        }
    }
}
function userlabellist()
{
    $sql = "SELECT * FROM userlabel";
    $result = mysqli_query(dbconnect(), $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $name = $row['name'];
            $class = $row['class'];
            echo "<option value='" . $id . "' class='$class'>" . $name . "</option>";
        }
    }
}
function taglabelshow($x)
{
    if (empty($x)) {
        return;
    }
    $myString = substr($x, 0, -1);
    $myArray = explode(',', $myString);
    foreach ($myArray as $ttt) {
        $sql = "SELECT * FROM label WHERE id='$ttt'";
        $result = mysqli_query(dbconnect(), $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $name = $row['name'];
                $class = $row['class'];
                echo "<span class='labeltag $class'>" . $name . "</span>";
            }
        }
    }
}
function userlabelshow($x)
{
    if (empty($x)) {
        return;
    }
    $myString = substr($x, 0, -1);
    $myArray = explode(',', $myString);
    foreach ($myArray as $ttt) {
        $sql = "SELECT * FROM userlabel WHERE id='$ttt'";
        $result = mysqli_query(dbconnect(), $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $name = $row['name'];
                $class = $row['class'];
                echo "<span class='labeltag $class'>" . $name . "</span>";
            }
        }
    }
}



function createfile($x)
{

    $myfile = fopen("mirror.html", "w") or die("Unable to open file!");
    $txt = $x;
    fwrite($myfile, $txt);

    fclose($myfile);
}



function jalalitime($x)
{

    $date = $x;
    $array = explode(' ', $date);
    list($year, $month, $day) = explode('-', $array[0]);
    list($hour, $minute, $second) = explode(':', $array[1]);
    $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
    $jalali_time = jdate("H:i", $timestamp, "", "Asia/Tehran", "en");
    return $jalali_time;
}
function jalalidate($x)
{

    $date = $x;
    $array = explode(' ', $date);
    list($year, $month, $day) = explode('-', $array[0]);
    list($hour, $minute, $second) = explode(':', $array[1]);
    $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
    $jalali_date = jdate("Y/m/d", $timestamp, "", "Asia/Tehran", "en");
    return $jalali_date;
}

function mahak($x)
{
    $gphone = substr($x, 1);
    $sql4 = "SELECT * FROM mahak WHERE mob1 LIKE '%" . $gphone . "%' OR mob2 LIKE '%" . $gphone . "%'   ";
    $result4 = mysqli_query(dbconnect(), $sql4);
    if (mysqli_num_rows($result4) > 0) {
        $n = 1;
        while ($row4 = mysqli_fetch_assoc($result4)) {
            $mname1 = $row4['name1'];
            $mname2 = $row4['name2'];

            if (strlen($phone) < 5) {
                break;
            }

            if ($n > 1) {
                echo ("<br>");
            }

            echo $mname1 . " " . $mname2;


            $n++;
        }
    }
}
function mahakcontact($x)
{
    $gphone = substr($x, 1);
    $sql4 = "SELECT * FROM mahak WHERE mob1 LIKE '%" . $gphone . "%' OR mob2 LIKE '%" . $gphone . "%'   ";
    $result4 = mysqli_query(dbconnect(), $sql4);
    if (mysqli_num_rows($result4) > 0) {
        $n = 1;
        while ($row4 = mysqli_fetch_assoc($result4)) {
            $mname1 = $row4['name1'];
            $mname2 = $row4['name2'];

            if (strlen($x) < 5) {
                break;
            }
            if ($n > 1) {
                echo ("<br>");
            }
            echo $mname1 . " " . $mname2;


            $n++;
        }
    }
}

function googlecontact($x)
{

    $gphone = substr($x, 1);
    $sql3 = "SELECT * FROM google WHERE mob1 LIKE '%" . $gphone . "%' OR mob2 LIKE '%" . $gphone . "%' OR mob3 LIKE '%" . $gphone . "%'  ";
    $result3 = mysqli_query(dbconnect(), $sql3);
    if (mysqli_num_rows($result3) > 0) {
        $n = 1;
        while ($row3 = mysqli_fetch_assoc($result3)) {
            $gname1 = $row3['name1'];
            $gname2 = $row3['name2'];
            $gname3 = $row3['name3'];

            if (strlen($x) < 5) {
                break;
            }
            if ($n > 1) {
                echo ("<br>");
            }
            echo $gname1 . " " . $gname2 . " " . $gname3;

            $n++;
        }
    }
}




function ifreconnect($x)
{
    $sql = "SELECT * FROM outgoing WHERE phone LIKE '" . $x . "%' ORDER BY  time DESC ";
    $result = mysqli_query(dbconnect(), $sql);
    if (mysqli_num_rows($result) > 0) {
        $n = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $n = $n + 1;

            $internal = $row['user'];

            if ($n > 1) {
                break;
            }

            echo '<img src=".././userimg/' . getidbyinternal($internal) . '.jpg" />';
        }
    } else {
        echo '<div class="no-reconnect">
                    <p>عدم ارتباط مجدد</p>
                    <i class="fas fa-window-close"></i>
                    </div>';
    }
}
