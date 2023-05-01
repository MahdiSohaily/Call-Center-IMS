<section class='relation' style="direction: rtl;">
    <div class="section serial-form">
        <form class="center" method="post">
            <input class="serial-input" type="text" name="serial" id="serial" placeholder="کد قطعه فنی را وارد کنید ..." onkeyup="search(this.value)">
        </form>
        <section id="match" style="direction: rtl;">
            <div id="s-result" class="list-group">
                <!-- The searched data is going to be added here -->
            </div>
        </section>
    </div>
    <div class="section">
        <section id="selected" style="direction: rtl;">
            <h2>موارد انتخاب شده:</h2>
            <!-- selected items are going to be appended here -->
        </section>
    </div>
    <div class="section">
        <form class='add-relation' action="#" method="post">
            <input class="r-input" placeholder="نام" type="text" name="name" id="name" required>
            <input list="browsers" id="myBrowser" name="myBrowser" />
            <datalist id="browsers">
                <option value="Chrome"></option>
                <option value="Firefox"></option>
                <option value="Opera"></option>
                <option value="Safari"></option>
                <option value="Microsoft Edge"></option>
            </datalist> <select class="r-input">
                <option value="" disabled selected> وضعیت کالای کورد نظر را انتخاب کنید</option>
                <option value="hurr">نو</option>
                <option value="hurr">در حد نو</option>
                <option value="hurr">کارکرده</option>
            </select>
            <input class="r-input" value="ثبت" type="submit" name="submit">
        </form>
    </div>
</section>

<script>
    let index = [];

    function myFunction(event) {
        const id = event.target.getAttribute("data-id");
        const remove = document.getElementById(id);

        const partnumber = event.target.getAttribute("data-partnumber");
        const price = event.target.getAttribute("data-price");
        const mobis = event.target.getAttribute("data-mobis");

        const result = document.getElementById('s-result');
        const selected = document.getElementById('selected');

        result.removeChild(remove);

        const item = `<div class='matched-item' id='` + id + `'>
                    <p>` + partnumber + `</p>
                    <i class='material-icons remove' onclick='remove(` + id + `)'>do_not_disturb_on</i>
                    </div>`;

        selected.innerHTML += (item);
        index.push(id);
    }

    function remove(id) {
        const item = document.getElementById(id);
        const selected = document.getElementById('selected');

        selected.removeChild(item);

        const r_id = index.indexOf(id);
        index.splice(r_id, 1);
    }

    function search(val) {
        let supermode = 0;
        const resultBox = document.getElementById('s-result')


        if (val.length > 6) {
            resultBox.innerHTML =
                "<img id='loading' src='<?php echo URL_ROOT . URL_SUBFOLDER ?>/public/img/loading.gif' alt=''>";
            axios.get('getdata/' + val)
                .then(response => {
                    resultBox.innerHTML = response.data;
                }).catch(error => {
                    console.log(error);
                })
        } else {
            resultBox.innerHTML = "";
        }
    }
</script>