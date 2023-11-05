 <?php require_once './layout/heroHeader.php';
    $phone = $_GET['phone'];

    $sql = "SELECT * FROM customer WHERE phone LIKE '" . $phone . "%'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $name = $row['name'];
            $family = $row['family'];
            $phone = $row['phone'];
            $vin = $row['vin'];
            $des = $row['des'];
            $address = $row['address'];
            $car = $row['car'];
            $kind = $row['kind'];
            $label = $row['label'];
            $userselect = $row['user'];
            echo '<div class="phone-status">شماره <span>' . $phone . '</span> با نام <span>' . $name . '</span> <span>' . $family . '</span> در سیستم ثبت می باشد .</div>';
            $isold = 1;
        }
    } // end while

    else {
        echo '<div class="phone-status no-save">شماره <span>' . $phone . '</span> در سیستم ثبت نمی باشد .</div>';
        $isold = 0;
    }
    ?>

 <style>
     textarea {
         line-height: 2;
     }
 </style>
 <div class="customer_info px-5">
     <div class="grid grid-cols-1 md:grid-cols-4 gap-6 lg:gap-6 lg:p-2 overflow-auto items-start">
         <div class="col-span-3">
             <h2 class="title text-lg">مشخصات مشتری</h2>
             <form class="save-contact form" action="php/save.php" method="get" autocomplete="off">
                 <div class="grid grid-cols-1 md:grid-cols-3  gap-6">
                     <div class="bg-gray-200 p-3">
                         <p>
                             شماره تماس
                         </p>
                         <input onkeyup="convertToEnglish(this)" id="phone" name="phone" type="text" value="<?php echo $phone ?>" readonly>
                     </div>
                     <div class="bg-gray-200 p-3">
                         <p>نام</p>
                         <input onkeyup="convertToPersian(this)" id="name" name="name" type="text" value="<?php echo  !empty($name) ?  $name :  '';  ?>">
                     </div>
                     <div class="bg-gray-200 p-3">
                         <p>نام خانوادگی</p>
                         <input onkeyup="convertToPersian(this)" id="last_name" name="family" type="text" value="<?php echo !empty($family) ? $family : ''; ?>">
                     </div>
                     <div class="bg-gray-200 p-3">
                         <p>شماره شاسی</p>


                         <input onkeyup="convertToEnglish(this)" name="vin" type="text" value="<?php echo !empty($vin) ? $vin : '' ?>">
                     </div>
                     <div class="bg-gray-200 p-3">
                         <p>ماشین</p>
                         <input name="car" type="text" value="<?php echo !empty($car) ? $car : '' ?>">
                     </div>
                     <div class="bg-gray-200 p-3" style="display: none;">
                         <p>نوع</p>
                         <input name="kind" type="text" value="null">
                     </div>
                     <div class="bg-gray-200 p-3">
                         <p>آدرس</p>
                         <textarea name="address"><?php echo !empty($address) ? $address : '' ?></textarea>
                     </div>
                     <div class="bg-gray-200 p-3">
                         <p>توضیحات مشتری</p>
                         <textarea name="des"><?php echo !empty($des) ? $des : '' ?></textarea>
                     </div>
                     <input name="isold" id="isold" type="hidden" value="<?php echo ($isold) ?>">
                     <div class="col-span-2 bg-gray-200	p-3">
                         <label class="flex justify-between" for="callinfo">
                             درج اطلاعات استعلام
                             <div class="flex justify-end gap-2" style="width: 200px;">
                                 <input style="width: 15px;" type="checkbox" name="pin" id="pin">
                                 <span>
                                     پین کردن استعلام
                                 </span>
                             </div>
                         </label>
                         <textarea id="call_info_text" class="callinfo" name="callinfo"></textarea>
                         <div class="flex items-start">
                             <div class="callinfobox-option">
                                 <div class="hover:cursor-pointer hover:bg-gray-400 hover:text-white"> درخواست بارنامه </div>
                                 <div class="hover:cursor-pointer hover:bg-gray-400 hover:text-white"> درخواست شماره کارت</div>
                                 <div class="hover:cursor-pointer hover:bg-gray-400 hover:text-white"> پیگیری پیک </div>
                                 <div class="hover:cursor-pointer hover:bg-gray-400 hover:text-white"> پیگیری روند فاکتور </div>
                                 <div class="hover:cursor-pointer hover:bg-gray-400 hover:text-white"> درخواست ثبت فاکتور </div>
                                 <div class="hover:cursor-pointer hover:bg-gray-400 hover:text-white"> ارجاع به واتساپ </div>
                                 <div class="hover:cursor-pointer hover:bg-gray-400 hover:text-white"> درخواست شماره واتساپ </div>
                                 <div class="hover:cursor-pointer hover:bg-gray-400 hover:text-white"> اطلاعات واریز وجه </div>
                             </div>
                             <i onclick="toGivenPrice()" title='انتقال کد به بخش جستجو' class="w-16 material-icons bg-indigo-500 text-white rounded-md py-3 px-5 hover:cursor-pointer hover:bg-indigo-600">arrow_backward</i>
                         </div>
                     </div>
                 </div>
                 <div class="bottom-bar">
                     <input class="customer-info-save" type="submit" value="ذخیره" id="sabt">
                     <div class="error">
                     </div>
                 </div>
             </form>
         </div>
         <!-- The main block for the search codes for giving price and displaying the already given prices to the specified client -->
         <div class="bg-gray-200">
             <h2 class="title text-lg bg-white"> کد های مد نظر برای جستجو
             </h2>
             <form method="post" target="_blank" class="" action="./report/showPriceReports.php">
                 <?php if (isset($id)) { ?>
                     <input type="text" name="givenPrice" value="givenPrice" id="form" hidden>
                     <input type="text" id="givenUser" name="user" value="<?php echo  $_SESSION["id"] ?>" hidden>
                     <input hidden name="customer" required id="givenCustomer" type="number" value="<?php echo $id ?>" />
                     <div class="bg-gray-200  p-3">

                         <textarea onkeyup="convertToEnglish(this)" onchange="filterCode(this)" style="border: 1px solid lightgray;" class="p-2 w-full ltr" id="givenCode" rows="7" name="code" required placeholder="لطفا کد های مورد نظر خود را در خط های مجزا قرار دهید"></textarea>
                         <div class="flex justify-between items-center">
                             <button type="submit" class="give-search-button"> جستجو</button>
                             <i onclick="toEstelam()" title='انتقال کد به بخش استعلام' class="material-icons bg-indigo-500 text-white rounded-md py-3 px-5 hover:cursor-pointer hover:bg-indigo-600">arrow_forward</i>
                         </div>
                     </div>

                     <!-- <button type="button" class="give-search-button" onclick="SearchGivenPrice()"> بررسی</button> -->
                 <?php } else {
                        echo '<div class="phone-status no-save">شماره <span>' . $phone . '</span> در سیستم ثبت نمی باشد .</div>';
                        $isold = 0;
                    } ?>
             </form>
         </div>
     </div>
 </div>
 <script>
     function filterCode(element) {
         const message = element.value;
         if (!message) {
             return '';
         }

         const codes = message.split("\n");

         const filteredCodes = codes.map(function(code) {
             code = code.replace(/\[[^\]]*\]/g, '');
             const parts = code.split(/[:,]/, 2);
             const rightSide = (parts[1] || '').replace(/[^a-zA-Z0-9 ]/g, ' ').trim();
             return rightSide ? rightSide : code.replace(/[^a-zA-Z0-9 ]/g, ' ').trim();
         }).filter(Boolean);

         const finalCodes = filteredCodes.filter(function(item) {
             const data = item.split(" ");
             if (data[0].length > 4) {
                 return item;
             }
         });

         const mappedFinalCodes = finalCodes.map(function(item) {
             const parts = item.split(' ');
             if (parts.length >= 2) {
                 const partOne = parts[0];
                 const partTwo = parts[1];
                 if (!/[a-zA-Z]{4,}/i.test(partOne) && !/[a-zA-Z]{4,}/i.test(partTwo)) {
                     return partOne + partTwo;
                 }
             }
             return parts[0];
         });

         const nonConsecutiveCodes = mappedFinalCodes.filter(function(item) {
             const consecutiveChars = /[a-zA-Z]{4,}/i.test(item);
             return !consecutiveChars;
         });

         console.log(nonConsecutiveCodes);

         element.value = nonConsecutiveCodes.map(function(item) {
             return item.split(' ')[0];
         }).join("\n") + "\n";
     }



     const price_textarea = document.getElementById('givenCode');
     const call_info_text = document.getElementById('call_info_text');


     function toEstelam() {
         call_info_text.value = price_textarea.value;
         price_textarea.value = null;
     }

     function toGivenPrice() {
         price_textarea.value = call_info_text.value;
         call_info_text.value = null;
     }


     function displayNotification(title, body) {
         // Check if the browser supports notifications
         if (!("Notification" in window)) {
             console.log("This browser does not support desktop notifications.");
             return;
         }

         // Check the current permission status
         if (Notification.permission === "granted") {
             // If permission has been granted, create a notification
             new Notification(title, {
                 body
             });
         } else if (Notification.permission !== "denied") {
             // Otherwise, request permission from the user
             Notification.requestPermission().then((permission) => {
                 if (permission === "granted") {
                     // If permission has been granted, create a notification
                     new Notification(title, {
                         body
                     });
                 }
             });
         }
     }
 </script>

 <?php
    if (isset($id)) {
        $givenPrice = (givenPrice($con, $id));
    }
    function givenPrice($conn, $id, $relation_exist = null)
    {
        $sql = "SELECT 
        prices.price, prices.partnumber, users.username, users.id as userID, prices.created_at
        FROM ((shop.prices 
        INNER JOIN callcenter.customer ON customer.id = prices.customer_id )
        INNER JOIN yadakshop1402.users ON users.id = prices.user_id)
        WHERE customer.id = '" . $id . "' ORDER BY prices.created_at DESC LIMIT 20";
        $result = mysqli_query($conn, $sql);


        $givenPrices = [];
        if (mysqli_num_rows($result) > 0) {
            while ($item = mysqli_fetch_assoc($result)) {
                array_push($givenPrices, $item);
            }
        }
        return  $givenPrices;
    }
    ?>
 <!-- Empty div with clear fix purpose to floating style which have been applied early -->
 <div class="clearfix"></div>


 <!-- END OF THE MAIN BLOCK FOR CODE SEARCHING -->


 <!-- START THE MODAL SECTION TO DISPLAY SEARCH RESULT -->
 <div id="givenPriceModal">
     <div class="ltr p-2">
         <i title="Close Modal" onclick="closeModal()" class="modal-operation material-icons hover:cursor-pointer text-gray-500 bold">close</i>
         <i title="Minimize Modal" onclick="minimizeModal()" class="modal-operation material-icons hover:cursor-pointer text-gray-500 bold">expand_more</i>
     </div>
     <div class="ltr" id="resultGiven"></div>
 </div>

 <div onclick="maximizeModal()" id="miniModal" class="p-2 bg-white hover:cursor-pointer">
     <p class="text-gray-500">نتایج جستجو</p>
     <i title="Minimize Modal" class="modal-operation material-icons hover:cursor-pointer text-gray-500 bold">expand_less</i>
 </div>
 <!-- END THE MODAL SECTION -->

 <script>
     // Global Modal instanse variable
     const givenPriceModal = document.getElementById("givenPriceModal");
     const miniModal = document.getElementById("miniModal");

     // A function to popup the modal and send the data to be searched
     function SearchGivenPrice() {
         givenPriceModal.style.display = 'block';

         const user = document.getElementById('givenUser').value;
         const customer = document.getElementById('givenCustomer').value;
         const code = document.getElementById('givenCode').value;
         const result = document.getElementById('resultGiven');

         const params = new URLSearchParams();
         params.append('givenPrice', 'givenPrice');
         params.append('user', user);
         params.append('customer', customer);
         params.append('code', code);

         result.innerHTML = `
                            <div class='full-page'>
                                <div>
                                <img class=' block w-10 mx-auto h-auto' src='./report/public/img/loading.png' alt='google'>
                                <p class="pt-2 text-gray-500">لطفا صبور باشید</p>
                                </div>
                            </div>
                                    `;

         axios.post("./report/showPriceAjaxReports.php", params)
             .then(function(response) {
                 result.innerHTML = response.data;
             })
             .catch(function(error) {

             });
     }

     //  A function to close the modal
     function closeModal() {
         givenPriceModal.style.display = 'none';
     }

     //  
     function minimizeModal() {
         givenPriceModal.style.display = 'none';
         miniModal.style.display = 'flex';
     }

     //  
     function maximizeModal() {
         givenPriceModal.style.display = 'block';
         miniModal.style.display = 'none';
     }



     //  A function to display the existing goods of specified code on mouse over
     const seekExist = (e) => {
         const element = e;
         if (element.hasAttribute("data-key")) {
             const partNumber = element.getAttribute('data-key');
             const brand = element.getAttribute('data-brand');

             const target = document.getElementById(partNumber + '-' + brand)
             target.style.display = 'block';
         }
     }

     //  A function to close the existing tooltip on mouse leave
     const closeSeekExist = (e) => {
         const element = e;
         if (element.hasAttribute("data-key")) {
             const partNumber = element.getAttribute('data-key');
             const brand = element.getAttribute('data-brand');

             const target = document.getElementById(partNumber + '-' + brand)
             target.style.display = 'none';
         }
     }

     // Global controllers for operations messages
     const form_success = document.getElementById('form_success');
     const form_error = document.getElementById('form_error');
     // Global price variable
     let price = null;

     // A function to update the global price while typing in the input feild
     function update_price(element) {
         price = element.value;
     }

     // A function to set the price to we don't have
     function donotHave(element) {
         price = 'نداریم';
         part = element.getAttribute('data-part');
         const input = document.getElementById(part + '-price');
         input.value = price;

         createRelation(element);
     }

     // A function to send a request in order to ask the price for specific code
     function askPrice(element) {
         // Accessing the form fields to get thier value for an ajax store operation
         const partNumber = element.getAttribute('data-part');
         const user_id = element.getAttribute('data-user');
         const customer_id = document.getElementById('customer_id').value;

         const params = new URLSearchParams();
         params.append('askPrice', 'askPrice');
         params.append('partNumber', partNumber);
         params.append('customer_id', customer_id);
         params.append('user_id', user_id);

         axios.post("./report/app/Controllers/GivenPriceAjax.php", params)
             .then(function(response) {
                 if (response.data == true) {
                     form_success.style.bottom = '10px';
                     setTimeout(() => {
                         form_success.style.bottom = '-300px';
                     }, 2000)
                 } else {
                     form_error.style.bottom = '10px';
                     setTimeout(() => {
                         form_error.style.bottom = '-300px';
                     }, 2000)
                 }
             })
             .catch(function(error) {});
     }

     // A function to create the relationship
     function createRelation(e) {
         // Accessing the form fields to get thier value for an ajax store operation
         const partNumber = e.getAttribute('data-part');
         const customer_id = document.getElementById('customer_id').value;
         const notification_id = document.getElementById('notification_id').value;

         const resultBox = document.getElementById('price-' + partNumber);

         resultBox.innerHTML = `<div class="price_box">
                                <img class=' block w-10 mx-auto h-auto' src='./report/public/img/loading.png' alt='google'>
                                <p class="pt-2 text-gray-500">لطفا صبور باشید</p>
                                </div> `;

         // Defining a params instance to be attached to the axios request
         const params = new URLSearchParams();
         params.append('store_price', 'store_price');
         params.append('partNumber', partNumber);
         params.append('customer_id', customer_id);
         params.append('notification_id', notification_id);
         params.append('price', price);

         axios.post("./report/app/Controllers/GivenPriceAjax.php", params)
             .then(function(response) {
                 if (response.data) {
                     setTimeout(() => {
                         resultBox.innerHTML = (response.data);
                     }, 2000)
                 } else {
                     setTimeout(() => {
                         location.reload();
                     }, 2000)
                 }
             })
             .catch(function(error) {

             });
     }

     // A function to set the price while cliking on the prices table
     function setPrice(element) {
         newPrice = element.getAttribute('data-price');
         part = element.getAttribute('data-part');
         const input = document.getElementById(part + '-price');
         input.value = newPrice;

         price = newPrice;
     }
 </script>

 <!-- THE END ( WRITTEN BY MAHDI REZAEI) -->

 <div id="mySizeChartModal" class="ebcf_modal">
     <div class="ebcf_modal-content">
         <span class="ebcf_close">&times;</span>
         <div class="message_container">
             <div class="messageBox">
                 <form action="#" method="post" onsubmit="event.preventDefault(); validate()">
                     <label for="messageBox">متن پیامک</label>
                     <br>
                     <textarea maxlength="160" name="message" id="messageBox" cols="60" rows="10" onkeyup="limitText()"></textarea>
                     <span name="charcount_text" id="charcount_text"></span>
                     <div class="submit_box">
                         <input class="submit" value="ارسال پیام" type="submit">

                     </div>
                 </form>
             </div>
             <div class="messages">
                 <ul>
                     <li class="message_item" id="hello" onclick="appendMessage(this.innerHTML)"></li>
                     <li class="message_item" id="hello" onclick="appendMessage(this.innerHTML)">الان میام</li>
                     <li class="message_item" id="hello" onclick="appendMessage(this.innerHTML)">لطفا منتظر باشید</li>
                     <li class="message_item" id="hello" onclick="appendMessage(this.innerHTML)">بسته شما رسید</li>
                     <li class="message_item" id="hello" onclick="appendMessage(this.innerHTML)">روزتان خوش</li>
                 </ul>
                 <div style="padding-block: 10px;" id="error_box"></div>
             </div>
         </div>
     </div>


 </div>

 <script>
     const phone = document.getElementById('phone');
     const name = document.getElementById('name');
     const last_name = document.getElementById('last_name');
     const cc = document.getElementById('charcount_text');

     // Get the modal
     const ebModal = document.getElementById('mySizeChartModal');

     // Get the button that opens the modal
     const ebBtn = document.getElementById("mySizeChart");

     // Get the <span> element that closes the modal
     const ebSpan = document.getElementsByClassName("ebcf_close")[0];

     // When the user clicks the button, open the modal 
     const messageModal = () => {
         ebModal.style.display = "block";

         const hello = document.getElementById('hello');
         hello.innerHTML = ` با سلام جناب آقای ${name.value} ${last_name.value}`
     }

     // When the user clicks on <span> (x), close the modal
     ebSpan.onclick = function() {
         ebModal.style.display = "none";
     }

     // When the user clicks anywhere outside of the modal, close it
     window.onclick = function(event) {
         if (event.target == ebModal) {
             ebModal.style.display = "none";
         }
     }

     const messageBox = document.getElementById('messageBox')
     const error_box = document.getElementById('error_box')



     function appendMessage(value) {
         const count = messageBox.value.length;
         const maxLength = messageBox.maxLength;
         const remaining = maxLength - count;

         if (remaining <= 0) {
             cc.innerHTML = `<p class='response'>پیام به حداکثر ظرفیت حروف رسید</p>`;
         } else if (remaining <= 50) {
             cc.innerHTML = `<p class='response'> ${remaining}حرف باقی مانده است </p>`;
         } else {
             cc.innerHTML = '';
         }

         const message_length = value.length;

         if (remaining > message_length) {
             messageBox.value += `${value} `;
         }
     }

     function validate() {
         const code = phone.value.slice(0, 2);
         if ('09' === code) {
             const validated = phone.value.replace(/\D+/g, '');
             const destination = `098` + validated.slice(1);
             const content = messageBox.value.trim();
             axios.get(`http://192.168.9.16/cgi/WebCGI?1500101=account=test&password=test1028&port=1&destination=${destination}&content=${content}`)
                 .then(function(response) {
                     error_box.innerHTML = `<p style="color: green;" class='response'>پیام با موفقیت ارسال گردید</p>`;
                 })
                 .catch(function(error) {
                     error_box.innerHTML = `<p style="color: red;" class='response'>!!! پیام ارسال نشد</p>`;
                 });

         } else {
             error_box.innerHTML = `<p class="error">ارسال پیام به شماره ${phone.value} امکان پذیر نمی باشد.</p>`
         }
     }

     // A function to limit the length of text message to Global standard
     function limitText() {
         const count = messageBox.value.length;
         const maxLength = messageBox.maxLength;
         const remaining = maxLength - count;

         const cc = document.getElementById('charcount_text');
         if (remaining <= 0) {
             cc.innerHTML = `<p class='response'>پیام به حداکثر ظرفیت حروف رسید</p>`;
         } else if (remaining <= 50) {
             cc.innerHTML = `<p class='response'>${remaining}حرف باقی مانده است </p>`;
         } else {
             cc.innerHTML = '';
         }
     }
 </script>

 <div class="box">
     <a class="click-to-call" href="#">تماس با مشتری</a>
     <a class="click-to-cancell" href="#">قطع تماس جاری</a>
     <a onclick="messageModal()" class="click-to-sms" href="#">ارسال پیامک</a>
     <a target="_blank" class="click-to-whatsapp" href="https://web.whatsapp.com/send/?phone=98<?php echo $phone ?>&text=با عرض سلام و وقت بخیر
%0a
از واحد فروش مجموعه یدک شاپ خدمتتون پیام ارسال شده است
%0a
اگر نیاز به مشاوره یا استعلام قیمت قطعات دارید می توانید برای ما پیام ارسال کنید ، کارشناسان فروش ما در سریع ترین حالت ممکن پاسخگوی شما هستند.
%0a
کارشناس شما : <?php echo getfamilybyid($_SESSION["id"]); ?>

&type=phone_number&app_absent=0">ارسال واتساپ</a>

     <form class="cartable-save-form " action="php/cartable-save.php" method="get" autocomplete="off">
         <div class="cartable-form-2 qmain">
             <div class="qtagselect isw360">
                 <select class="qtagselect__select" name="label[]" id="label" multiple>

                     <?php taglabellist() ?>

                 </select>
                 <script>
                     <?php
                        $myString =     substr($label, 0, -1);
                        $myArray = explode(',', $myString);
                        foreach ($myArray as $ttt) {
                            $ttt = (int) $ttt - 1;
                            echo "$('#label option:eq($ttt)').attr('selected', 'selected');";
                        }
                        ?>
                 </script>

             </div>

         </div>
         <div class="cartable-form-3 qmain2">
             <input name="phone" type="text" value="<?php echo $phone ?>" hidden>
             <div class="quserselect isw360">
                 <select class="quserselect__select" name="userselector[]" id="userselector" multiple>
                     <?php userlabellist() ?>
                 </select>

                 <script>
                     <?php

                        $myString =     substr($userselect, 0, -1);
                        $myArray = explode(',', $myString);
                        foreach ($myArray as $ttt) {
                            $ttt = (int) $ttt - 1;
                            echo "$('#userselector option:eq($ttt)').attr('selected', 'selected');";
                        }
                        ?>
                 </script>
             </div>
         </div>

         <div class="cartable-form-1">
             <select name="cartable-pos" id="cartable-pos">
                 <option value="0">انتخاب کارتابل</option>
                 <option value="1">نیاز به پیگیری</option>
                 <option value="2">نیاز به قفلی</option>

                 <option value="3">داستان شده</option>
                 <option value="0">حذف از کارتابل</option>

             </select>

         </div>

         <input type="submit" value="ذخیره لیبل">

         <div style="clear:both"></div>
     </form>
 </div>

 <div class="clearfix"></div>

 <div class="actionsHistory">
     <div id="child_one">
         <h2 class="title">استعلام های قبلی</h2>

         <div class="box-keeper">

             <table class="customer-list">
                 <tr>
                     <th>اطلاعات</th>
                     <th>کاربر ثبت کننده</th>
                     <th>زمان</th>
                 </tr>
                 <?php
                    $sql2 = "SELECT * FROM record WHERE phone LIKE '" . $phone . "%' ORDER BY  time DESC LIMIT 300";
                    $result2 = mysqli_query($con, $sql2);
                    if (mysqli_num_rows($result2) > 0) {
                        while ($row2 = mysqli_fetch_assoc($result2)) {
                            $time = $row2['time'];
                            $callinfo = $row2['callinfo'];
                            $user = $row2['user'];
                    ?>
                         <tr>
                             <td class="tiny-text record-info"><?php echo nl2br($callinfo) ?></td>
                             <td class="tiny-text record-user">
                                 <?php

                                    $con2 = mysqli_connect('localhost', 'root', '', 'yadakshop1402');

                                    if (!$con2) {
                                        die('Could not connect: ' . mysqli_error($con2));
                                    }
                                    mysqli_set_charset($con2, "utf8");
                                    $sql3 = "SELECT * FROM users WHERE id=$user";
                                    $result3 = mysqli_query($con2, $sql3);
                                    if (mysqli_num_rows($result3) > 0) {
                                        while ($row3 = mysqli_fetch_assoc($result3)) {
                                            $id = $row3['id'];
                                            $name = $row3['name'];
                                            $family = $row3['family'];
                                            echo "<img title='$name $family' class='userImage' src='../userimg/$id.jpg' alt='userimage'>";
                                        }
                                    }
                                    date_default_timezone_set("Asia/Tehran");
                                    $create = date($time);

                                    $now = new DateTime(); // current date time
                                    $date_time = new DateTime($create); // date time from string
                                    $interval = $now->diff($date_time); // difference between two date times
                                    $days = $interval->format('%a'); // difference in days
                                    $hours = $interval->format('%h'); // difference in hours
                                    $minutes = $interval->format('%i'); // difference in minutes
                                    $seconds = $interval->format('%s'); // difference in seconds

                                    $text = '';

                                    if ($days) {
                                        $text .= " $days روز و ";
                                    }

                                    if ($hours) {
                                        $text .= "$hours ساعت ";
                                    }

                                    if (!$days && $minutes) {
                                        $text .= "$minutes دقیقه ";
                                    }

                                    if (!$days && !$hours && $seconds) {
                                        $text .= "$seconds ثانیه ";
                                    }

                                    $text = "$text قبل";
                                    ?>
                             </td>

                             <td class="tiny-text record-time"><?php echo $text; ?></td>
                         </tr>
                 <?php

                        }
                    } // end while


                    else {
                        echo '<td colspan="4">هیچ اطلاعاتی موجود نیست</td>';
                    }
                    ?>
             </table>
         </div>
     </div>
     <div id="child_two">
         <h2 class="title">تماس های قبلی</h2>
         <div class="box-keeper">
             <table class="customer-list">
                 <tr>
                     <th>پاسخ دهنده</th>
                     <th>زمان</th>
                 </tr>
                 <?php
                    $pretime = "";

                    $sql30 = "SELECT * FROM incoming WHERE phone LIKE '" . $phone . "%' ORDER BY  time DESC  LIMIT 200";
                    $result30 = mysqli_query($con, $sql30);
                    if (mysqli_num_rows($result30) > 0) {
                        while ($row30 = mysqli_fetch_assoc($result30)) {
                            $time = $row30['time'];
                            $user = $row30['user'];
                            $id = $row30['callid'];
                            $status = $row30['status'];
                            $start = $row30['starttime'];
                            $end = $row30['endtime'];




                            $interval1 =    nishatimedef(date('Y/m/d H:i:s'), $time);
                            $interval2 =    nishatimedef($start, $end);




                            if ($status == 0 and $pretime == $time) {
                                $pretime = $time;

                                continue;
                            }

                            $pretime = $time;


                    ?>
                         <tr>
                             <td class=" tiny-text record-info">
                                 <?php
                                    if ($status == 0) {
                                        echo ("<div class='answer-x'>X</div>");
                                    } else {
                                        echo (getnamebyinternal($user) . "<div class='answer-tik'>&#10004;</div><div class='call-duration'>" . format_calling_time($interval2) . "</div>");
                                    }
                                    ?>
                             </td>
                             <td class=" tiny-text record-time">
                                 <?php
                                    date_default_timezone_set("Asia/Tehran");
                                    $create = date($time);

                                    $now = new DateTime(); // current date time
                                    $date_time = new DateTime($create); // date time from string
                                    $interval = $now->diff($date_time); // difference between two date times
                                    $days = $interval->format('%a'); // difference in days
                                    $hours = $interval->format('%h'); // difference in hours
                                    $minutes = $interval->format('%i'); // difference in minutes
                                    $seconds = $interval->format('%s'); // difference in seconds

                                    $text = '';

                                    if ($days) {
                                        $text .= " $days روز و ";
                                    }

                                    if ($hours) {
                                        $text .= "$hours ساعت ";
                                    }

                                    if (!$days && $minutes) {
                                        $text .= "$minutes دقیقه ";
                                    }

                                    if (!$days && !$hours && $seconds) {
                                        $text .= "$seconds ثانیه ";
                                    }

                                    echo "$text قبل";
                                    ?></td>
                         </tr>
                 <?php
                        }
                    } // end while
                    else {
                        echo '<td colspan="4">هیچ اطلاعاتی موجود نیست</td>';
                    }
                    ?>
             </table>
         </div>
     </div>
     <div id="child_three">
         <h2 class="title">قیمت های داده شده</h2>
         <?php if (isset($id)) { ?>
             <div class="box-keeper">
                 <table id="g_price" class="customer-list">
                     <tr>
                         <th>قیمت</th>
                         <th>کد فنی</th>
                         <th>قیمت دهنده</th>
                         <th>زمان</th>
                     </tr>
                     <tbody>
                         <?php
                            if (isset($givenPrice)) {
                            ?>
                             <?php foreach ($givenPrice as $price) { ?>
                                 <?php if ($price['price'] !== null) {
                                    ?>
                                     <tr class="tiny-text min-w-full mb-1 ?>">
                                     <?php  } ?>
                                     <td class="">
                                         <?php echo $price['price'] === null ? 'ندارد' : $price['price']  ?>
                                     </td>
                                     <td class="">
                                         <?php echo $price['partnumber']; ?>
                                     </td>
                                     <td style="width:100px;">
                                         <img title="<?php echo $price['username'] ?>" class="userImage" src="../userimg/<?php echo $price['userID'] ?>.jpg" alt="userimage">
                                     </td>
                                     <td class="time">
                                         <?php
                                            date_default_timezone_set("Asia/Tehran");
                                            $create = date($price['created_at']);

                                            $now = new DateTime(); // current date time
                                            $date_time = new DateTime($create); // date time from string
                                            $interval = $now->diff($date_time); // difference between two date times
                                            $days = $interval->format('%a'); // difference in days
                                            $hours = $interval->format('%h'); // difference in hours
                                            $minutes = $interval->format('%i'); // difference in minutes
                                            $seconds = $interval->format('%s'); // difference in seconds

                                            $text = '';

                                            if ($days) {
                                                $text .= " $days روز و ";
                                            }

                                            if ($hours) {
                                                $text .= "$hours ساعت ";
                                            }

                                            if (!$days && $minutes) {
                                                $text .= "$minutes دقیقه ";
                                            }

                                            if (!$days && !$hours && $seconds) {
                                                $text .= "$seconds ثانیه ";
                                            }

                                            echo "$text قبل";
                                            ?>
                                     </td>
                                     </tr>
                                 <?php
                                } ?>
                             <?php } else { ?>
                                 <tr class="">
                                     <td colspan="4" scope="col" class="not-exist">
                                         موردی برای نمایش وجود ندارد !!
                                     </td>
                                 </tr>
                             <?php } ?>
                     </tbody>



                 </table>
             </div>
         <?php } ?>
     </div>
 </div>
 <div class="space"></div>
 <script>
     $('.qtagselect__select').tagselect();
     $('.quserselect__select').userselect();
 </script>
 <script>
     $(document).ready(function() {
         $(".click-to-call").click(function() {



             window.open('http://admin:1028400NRa@<?php echo getip($_SESSION["id"]) ?>/servlet?key=number=<?php echo $phone ?>&outgoing_uri=@192.168.9.10', 'برقراری تماس', 'width=400,height=400')


         });

         $(".click-to-cancell").click(function() {



             window.open('http://admin:1028400NRa@<?php echo getip($_SESSION["id"]) ?>/servlet?key=CALLEND', 'برقراری تماس', 'width=400,height=400')


         });
     });
 </script>
 <?php
    require_once './layout/heroFooter.php';
