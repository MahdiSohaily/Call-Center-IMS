<script src="js/jquery.min.js?v=<?php echo (rand()) ?>"></script>
<script src="js/save.js?v=<?php echo (rand()) ?>"></script>

<link rel="stylesheet" href="css/style.css?v=<?php echo (rand()) ?>" type="text/css" media="all" />

<?php
$phone = $_GET['phone'];
?>

<h2 class="title">ذخیره شماره تلفن</h2>
<form class="save-contact form" action="php/save.php" method="get" autocomplete="off">>

    <div class="form-keeper">
        <div>
            <p>شماره تماس</p>

            <input name="number" type="text" value="<?php echo $phone ?>">
        </div>
        <div>
            <p>نام</p> <input name="name" type="text">
        </div>

        <div>
            <p>نام خانوادگی</p>
            <input name="family" type="text">
        </div>
        <div>
            <p>شهر</p>
            <input type="text">
        </div>
        <div>
            <p>آدرس</p>
            <textarea></textarea>
        </div>
        <div>
            <p>ماشین</p>
            <input type="text">
        </div>
        <div>
            <p>نوع</p>
            <input type="text">
        </div>
        <div>
            <p>توضیحات</p>
            <textarea></textarea>
        </div>
    </div>

    <div class="bottom-bar">
        <input type="submit" value="ذخیره" id="sabt">
        <div class="error">
        </div>
    </div>
</form>