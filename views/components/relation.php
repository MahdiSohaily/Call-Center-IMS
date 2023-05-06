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
        <form class='add-relation' action="#" method="post" onsubmit="event.preventDefault(); send()">
            <input class="r-input" type="text" name="mode" value="create" hidden required>

            <input class="r-input" placeholder="نام" type="text" name="name" id="name" required>
            <select id="car_id" class="r-input" name="car_id" required>
                <?php
                if ($cars) {
                    foreach ($cars as $car) {
                        echo '<option value="' . $car['id'] . '">' . $car['name'] . '</option>';
                    }
                }
                ?>
            </select>
            <select name="status" id="status" class="r-input" required>
                <option value="نو">نو</option>
                <option value="در حد نو">در حد نو</option>
                <option value="کارکرده">کارکرده</option>
            </select>
            <input class="r-input bg-green" value="ثبت" type="submit" name="submit">
        </form>
    </div>
</section>

<script>
    // All the needed variables for building relations
    let index = [];
    let name = '';
    let car_id = '';
    let status = '';

    // A function for searching goods base on serial number
    function search(val) {
        const resultBox = document.getElementById('s-result');
        const selected = document.getElementById('selected');

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
            selected.innerHTML = "";
        }
    }

    // Enable search option for select elements
    $(document).ready(function() {
        //change select boxes to select mode to be searchable
        $("select").select2();
    });

    // A function to add a good to the relation box
    function add(event) {
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

    // A function to load data a good to the relation box
    function load(event) {
        const id = event.target.getAttribute("data-id");
        const remove = document.getElementById(id);

        const result = document.getElementById('s-result');
        const selected = document.getElementById('selected');

        result.removeChild(remove);

        if (id) {
            selected.innerHTML =
                "<img id='loading' src='<?php echo URL_ROOT . URL_SUBFOLDER ?>/public/img/loading.gif' alt=''>";
            axios.get('loadData/' + id)
                .then(response => {
                    selected.innerHTML = response.data;
                    axios.get('loadDescription/' + id)
                        .then(response => {
                            setValue(response.data);
                        }).catch(error => {
                            console.log(error);
                        })
                }).catch(error => {
                    console.log(error);
                })
        } else {
            selected.innerHTML = "";
        }
    }

    // A function to remove added goods from relation box
    function remove(id) {
        const item = document.getElementById(id);
        const selected = document.getElementById('selected');

        selected.removeChild(item);

        const r_id = index.indexOf(id);
        index.splice(r_id, 1);
    }

    // Get the selected input value to send data;
    function setValue(data) {
        const name = document.getElementById('name');
        const car_id = document.getElementById('car_id');
        const status = document.getElementById('status');
        console.log(data.car);

        name.value = data.name;
        car_id.value = data.car;
        status.value = data.status;
    }

    // A function to handle the form submission
    function send() {
        const data = [index, name, car_id, status];

        axios.post('/saveRelation', {
                firstName: 'Finn',
                lastName: 'Williams'
            })
            .then((response) => {
                console.log(response);
            }, (error) => {
                console.log(error);
            });
    }
</script>