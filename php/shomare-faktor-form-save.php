<?php
// Initialize the session
session_name("MyAppSession");
require_once("db.php");

$value1 = $_GET['kharidar'];
$value2 =  $_SESSION["id"];








$sql2 = "SELECT MAX(shomare) AS invnum FROM shomarefaktor;";

$result2 = mysqli_query($con, $sql2);
while ($row2 = mysqli_fetch_assoc($result2)) {
    $invnum = $row2['invnum'] + 1;
}






$sql = "INSERT INTO shomarefaktor (kharidar,user,shomare) VALUES ('$value1', '$value2','$invnum');";
$result = mysqli_query($con, $sql);






if (!$result) {
    echo "Error MySQLI QUERY: " . mysqli_error($con) . "";
    die();
} else {
?>
    <p>
        شماره فاکتور
        <span title="کپی شماره فاکتور" data-bill="<?= $invnum ?>" onClick="copyBillNumber(this)" class="shomare-faktor-copy">
            <i class="fas fa-paste"></i>
            <?= $invnum ?>
        </span>
        با موفقیت برای
        <strong class="shomare-faktor-name"><?= $value1 ?></strong>
        ثبت شد!
    </p>
<?php
}

?>
<script>
    function copyBillNumber(element) {
        const billNumber = element.getAttribute('data-bill');
        copyToClipboard(billNumber);

        element.innerHTML = '<i class="fas fa-check"></i> ' + 'انجام شد';
        setTimeout(() => {
            element.innerHTML = '<i class="fas fa-check" style="color:red"></i> ' + billNumber;
        }, 1500);

        const date = ($("#invoice_time").attr("data-gdate"));
        var params = new URLSearchParams();
        params.append('getNewFactor', 'getNewFactor');
        params.append('date', date);
        axios.post("./factorAjax.php", params)
            .then(function(response) {
                resultBox.innerHTML = response.data;
            })
            .catch(function(error) {
                console.log(error);
            });
    }
</script>