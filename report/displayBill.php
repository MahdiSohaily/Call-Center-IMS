<?php
require_once './config/config.php';
require_once './database/connect.php';
require_once('./views/Layouts/header.php');
?>
<style>
    @media print {
        #page_header {
            display: none;
        }

        @page :footer {
            display: none !important;
        }

        @page :header {
            display: none !important;
        }

        @page {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        main {
            padding-block: 10px !important;
            margin: 0 !important;
        }

        * {
            box-shadow: none !important;
        }

        #nav {
            display: none !important;
        }

        #side_nav {
            display: none !important;
        }

        #print_container {
            width: 100vw !important;
            height: 100vh !important;
        }

        #print_modal {
            background-color: white;
        }
    }
</style>
<div class="container bg-white rounded-lg shadow-md p-2 mb-5">
    <div class="rtl grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 px-4 mb-4 p-2 w-full">
        <table class="min-w-full border border-gray-800 text-gray-400 mb-5">
            <thead>
                <tr class="bg-gray-800 text-white text-center border-b mb-2">
                    <th colspan="2" class="py-2">
                        مشخصات خریدار
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-2 px-4 text-white bg-gray-800">نام</td>
                    <td class="py-2 px-4">
                        <input class="w-full p-2 border" type="hidden" name="id" id="id">
                        <input class="w-full p-2 border" type="hidden" name="type" id="mode" value='create'>
                        <input class="w-full p-2 border text-gray-500" placeholder="اسم کامل مشتری را وارد کنید..." type="text" name="name" id="name">
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-4 text-white bg-gray-800">تلفون</td>
                    <td class="py-2 px-4">
                        <input class="w-full p-2 border text-gray-500" placeholder="093000000000" type="text" name="" id="phone">
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-4 text-white bg-gray-800">آدرس</td>
                    <td class="py-2 px-4">
                        <textarea name="address" id="address" cols="30" rows="4" class="border p-2 w-full text-gray-500" placeholder="آدرس مشتری"></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-4 text-white bg-gray-800">ماشین</td>
                    <td class="py-2 px-4">
                        <input class="w-full p-2 border text-gray-500" placeholder="نوعیت ماشین مشتری را مشخص کنید" type="text" name="" id="car">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="p-2 w-full col-span-3">
        <div class="container mx-auto">
            <table class="min-w-full border border-gray-800 text-gray-400">
                <thead>
                    <tr class="bg-gray-800">
                        <th class="py-2 px-4 border-b text-white w-10">ردیف</th>
                        <th class="py-2 px-4 border-b text-white">کد فنی</th>
                        <th class="py-2 px-4 border-b text-white">نام قطعه</th>
                        <th class="py-2 px-4 border-b text-white"> تعداد</th>
                        <th class="py-2 px-4 border-b text-white"> قیمت</th>
                        <th class="py-2 px-4 border-b text-white"> قیمت کل</th>
                        <th class="py-2 px-4 border-b w-12 h-12 font-medium">
                            <img class="bill_icon" src="./public/img/setting.svg" alt="settings icon">
                        </th>
                    </tr>
                </thead>
                <tbody id="bill_body">
                </tbody>
            </table>
        </div>
    </div>
    <div class="rtl grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 px-4 mb-4 p-2 w-full">
        <table class="min-w-full border border-gray-800 text-gray-400 mb-5">
            <thead>
                <tr class="bg-gray-800 text-white text-center border-b mb-2">
                    <th colspan="2" class="py-2">
                        اطلاعات فاکتور
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-2 px-4 text-white bg-gray-800">تعداد اقلام</td>
                    <td class="py-2 px-4">
                        <input readonly class="w-full p-2 border text-gray-500" placeholder="تعداد اقلام فاکتور" type="text" name="quantity" id="quantity">
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-4 text-white bg-gray-800">جمع کل</td>
                    <td class="py-2 px-4">
                        <input readonly class="w-full p-2 border text-gray-500" placeholder="جمع کل اقلام فاکتور" type="text" name="totalPrice" id="totalPrice">
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-4 text-white bg-gray-800">تخفیف</td>
                    <td class="py-2 px-4">
                        <input class="w-full p-2 border text-gray-500" placeholder="0" type="number" name="discount" id="discount">
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-4 text-white bg-gray-800">مالبات (۰٪)</td>
                    <td class="py-2 px-4">
                        <input class="w-full p-2 border text-gray-500" placeholder="0" type="number" name="tax" id="tax">
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-4 text-white bg-gray-800">عوارض</td>
                    <td class="py-2 px-4">
                        <input class="w-full p-2 border text-gray-500" placeholder="0" type="number" name="withdraw" id="withdraw">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="bg-gray-800 text-white h-10 border-top">
                        <p id="total_in_word" class="px-3 text-sm"></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php
require_once('./views/Layouts/footer.php');
