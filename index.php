 <?php
    require_once './app/controller/DashboardController.php';
    require_once './layout/heroHeader.php';
    require_once './utilities/helpers.php';
    ?>
 <style>
     body {
         background-color: #F3F4F6 !important;
     }

     .bg-gradient::after {
         background: radial-gradient(600px circle at var(--mouse-x) var(--mouse-y), rgba(0, 0, 0, 0.6), transparent 20%) !important;
     }
 </style>

 <!-- ------------------------------------------------ Dashboard card section ---------------------------------------------------- -->

 <section class="mx-auto p-5 bg-gray-100">
     <div class="grid grid-cols-1 gap-5 mt-6 sm:grid-cols-2 lg:grid-cols-4">
         <div class="p-4 transition-shadow bg-white rounded-lg shadow-sm hover:shadow-lg">
             <div class="flex items-start justify-between">
                 <div class="flex flex-col space-y-2">
                     <span class="text-gray-800">مجموع کاربران</span>
                     <span class="text-lg font-semibold"><?= $totalUsers ?></span>
                 </div>
                 <img class="rounded-md w-16 h-16" src="<?= ('./public/img/user.svg') ?>" alt="">
             </div>
             <div>
                 <span class="inline-block px-2 text-sm text-white bg-green-500 ml-1 rounded">14%</span>
                 <a href="./report/usersManagement.php" class="text-blue-500 underline">مدیریت کاربران</a>
             </div>
         </div>
         <div class="p-4 transition-shadow bg-white rounded-lg shadow-sm hover:shadow-lg">
             <div class="flex items-start justify-between">
                 <div class="flex flex-col space-y-2">
                     <span class="text-gray-800">مجموع فاکتور های ثبت شده</span>
                     <span class="text-lg font-semibold"><?= $totalFactors ?></span>
                 </div>
                 <img class="rounded-md w-16 h-16" src="<?= ('./public/img/invoice.svg') ?>" alt="">
             </div>
             <div>
                 <span class="inline-block px-2 text-sm text-white bg-green-500 ml-1 rounded">14%</span>
                 <a href="./report/factor_new.php" class="text-blue-500 underline">ثبت فاکتور جدید</a>
             </div>
         </div>
         <div class="p-4 transition-shadow bg-white rounded-lg shadow-sm hover:shadow-lg">
             <div class="flex items-start justify-between">
                 <div class="flex flex-col space-y-2">
                     <span class="text-gray-800">مجموع اقلام وارد شده</span>
                     <span class="text-lg font-semibold"><?= $totalGoods ?></span>
                 </div>
                 <img class="rounded-md w-16 h-16" src="<?= ('./public/img/receive.svg') ?>" alt="">
             </div>
             <div>
                 <span class="inline-block px-2 text-sm text-white bg-green-500 ml-1 rounded">14%</span>
                 <a href="../1402/vorodkala-report.php?interval=3" class="text-blue-500 underline">گزارش اقلام وارده</a>
             </div>
         </div>
         <div class="p-4 transition-shadow bg-white rounded-lg shadow-sm hover:shadow-lg">
             <div class="flex items-start justify-between">
                 <div class="flex flex-col space-y-2">
                     <span class="text-gray-800">مجموع اقلام به فروش رسیده</span>
                     <span class="text-lg font-semibold"><?= $totalSold ?></span>
                 </div>
                 <img class="rounded-md w-16 h-16" src="<?= ('./public/img/deliver.svg') ?>" alt="">
             </div>
             <div>
                 <span class="inline-block px-2 text-sm text-white bg-green-500 ml-1 rounded">14%</span>
                 <a href="../1402/khorojkala-report.php?interval=3" class="text-blue-500 underline">گزارش اقلام خارجه</a>
             </div>
         </div>
     </div>
 </section>

 <!-- ---------------------------------------------- Dashboard users and calender ---------------------------------------------------- -->

 <section class="mx-auto rtl bg-gray-100">
     <div class="grid grid-cols-1 md:grid-cols-2 px-5 gap-5">
         <div class="bg-white rounded-lg p-5">
             <div class="border border-dashed border-gray-800 flex flex-col items-center h-full rounded-lg">
                 <div class="overflow-x-auto shadow-md sm:rounded-lg w-full h-full">
                     <table class="w-full text-sm text-left rtl:text-right text-gray-800 h-full">
                         <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                             <tr>
                                 <th scope="col" class="text-right text-gray-800 px-6 py-3">
                                     شهرت
                                 </th>
                                 <th scope="col" class="text-right text-gray-800 px-6 py-3">
                                     آي پی آدرس
                                 </th>
                                 <th scope="col" class="text-right text-gray-800 px-6 py-3">
                                     داخلی
                                 </th>
                                 <th scope="col" class="text-right text-gray-800 px-6 py-3">
                                     مدت زمان مکالمه
                                 </th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php foreach (getCallCenterUsers() as $user) :
                                    $profile = '../userimg/default.png';
                                    if (file_exists("../userimg/" . $user['id'] . ".jpg")) {
                                        $profile = "../userimg/" . $user['id'] . ".jpg";
                                    }
                                ?>
                                 <tr class="border-b/10 hover:bg-gray-50 rtl ">
                                     <th scope="row" class="flex items-center px-6 py-4 text-gray-800 whitespace-nowrap">
                                         <img class="w-10 h-10 rounded-full" src="<?= $profile ?>" alt="Jese image">
                                         <div class="ps-3">
                                             <div class="text-base font-semibold text-right"><?= $user['name'] . ' ' . $user['family'] ?></div>
                                             <div class="font-normal text-gray-500 py-1 text-right"><?= $user['username'] ?></div>
                                         </div>
                                     </th>
                                     <td class="px-6 py-4 text-right text-sm">
                                         <?= $user['ip'] ?>
                                     </td>
                                     <td class="px-6 py-4 text-right text-sm">
                                         <div class="flex items-center">
                                             <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div>
                                             <?= $user['internal'] ?>
                                         </div>
                                     </td>
                                     <td class="px-6 py-4 text-right text-sm">
                                         ۱ ساعت و ۴۵ دقیقه
                                     </td>
                                 </tr>
                             <?php endforeach; ?>
                         </tbody>
                     </table>
                 </div>

             </div>
         </div>
         <div class="bg-white rounded-lg p-5">
             <div class="border border-dashed border-gray-900 flex flex-col items-center justify-center p-5 rounded-lg">
                 <h1 class="text-2xl font-bold text-center text-gray-800 mb-2"><?= jdate('l J F') . ' - ' . jdate('Y/m/d'); ?></h1>
                 <p class="flex items-end mt-2 text-base text-center text-gray-500 gap-x-2">
                     <span class="ml-3 text-sm"> دور گردون گر دو روزی بر مراد ما نرفت </span>
                     <span class="mr-3 text-sm"> دائما یکسان نباشد حال دوران غم مخور</span>
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-red-500 shrink-0">
                         <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                     </svg>
                 </p>

                 <div class="grid w-full max-w-xl grid-cols-7 gap-4 mx-auto mt-6">
                     <p class="flex items-center justify-center h-12 text-blue-300 text-sm">شنبه</p>
                     <p class="flex items-center justify-center h-12 text-blue-300 text-sm">یکشنبه</p>
                     <p class="flex items-center justify-center h-12 text-blue-300 text-sm">دوشنبه</p>
                     <p class="flex items-center justify-center h-12 text-blue-300 text-sm">سه شنبه</p>
                     <p class="flex items-center justify-center h-12 text-blue-300 text-sm">چهار شنبه</p>
                     <p class="flex items-center justify-center h-12 text-blue-300 text-sm">پنجشنبه</p>
                     <p class="flex items-center justify-center h-12 text-blue-300 text-sm">جمعه</p>
                 </div>

                 <div class="grid w-full max-w-xl grid-cols-7 gap-6 mx-auto">
                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">1</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">2</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">3</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">4</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">5</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">6</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">7</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">8</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">9</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">10</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">11</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">12</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">13</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">14</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">15</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">16</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">17</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">18</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">19</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">20</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">21</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">22</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">23</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">24</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">25</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">26</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">27</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">28</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">29</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">30</div>
                     </div>

                     <div class="relative w-full h-10 cursor-pointer hover:scale-110 box bg-gradient after:absolute after:inset-0 after:z-10 after:h-full after:w-full after:transition-opacity after:duration-500 hover:bg-gray-800">
                         <div class="absolute inset-[3px] z-20 flex items-center justify-center bg-white text-gray-800">31</div>
                     </div>
                 </div>

             </div>
         </div>
     </div>
 </section>
 <?php
    require_once './layout/heroFooter.php';
