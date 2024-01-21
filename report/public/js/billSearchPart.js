function searchCustomer(pattern) {
  pattern = pattern.trim();
  if (pattern.length > 3) {
    customer_results.innerHTML = `<tr class=''>
                                        <div class='w-full h-52 flex justify-center items-center'>
                                            <img class=' block w-10 mx-auto h-auto' src='./public/img/loading.png' alt='google'>
                                        </div>
                                     </tr>`;
    var params = new URLSearchParams();
    params.append("customer_search", "customer_search");
    params.append("pattern", pattern);

    if (pattern.length > 3) {
      axios
        .post("./app/Controllers/BillController.php", params)
        .then(function (response) {
          let template = "";
          if (response.data.length > 0) {
            for (const customer of response.data) {
              template +=
                `
                                <div class="w-full flex justify-between items-center shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border border-gray-300">
                                    <p class="text-md font-semibold text-gray-600">
                                        ` +
                customer.name +
                `
                                        ` +
                customer.family +
                `
                                    </p>
                                    <p class="text-md font-semibold text-gray-600">
                                        ` +
                customer.phone +
                `
                                    </p>
                                        <i  data-id="` +
                customer.id +
                `" 
                                            data-name="` +
                customer.name +
                `" 
                                            data-family="` +
                customer.family +
                `" 
                                            data-phone="` +
                customer.phone +
                `"
                                            data-address="` +
                customer.address +
                `"
                                            data-car="` +
                customer.car +
                `"
                                            onclick="selectCustomer(this)"
                                                class="material-icons bg-green-600 cursor-pointer rounded-circle hover:bg-green-800 text-white">add
                                        </i>
                                    </div>
                                `;
            }
          } else {
            template += `
                                <div class="w-full flex justify-between items-center shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border border-gray-300">
                                    <p class="text-md font-semibold text-gray-600">
                                       مشتری ای با مشخصات وارده در سیستم موجود نیست
                                    </p>
                                    </div>
                                `;
          }
          customer_results.innerHTML = template;
        })
        .catch(function (error) {
          console.log(error);
        });
    } else {
      customer_results.innerHTML = "کد فنی وارد شده فاقد اعتبار است";
    }
  } else {
    customer_results.innerHTML = "";
  }
}

function selectCustomer(customer) {
  customerInfo.id = customer.getAttribute("data-id");
  customerInfo.mode = "update";
  customerInfo.name = customer.getAttribute("data-name").trim();
  customerInfo.family = customer.getAttribute("data-family").trim();
  customerInfo.phone = customer.getAttribute("data-phone");
  customerInfo.car = customer.getAttribute("data-car");
  customerInfo.address = customer.getAttribute("data-address");

  document.getElementById("id").value = customerInfo.id;
  document.getElementById("mode").value = customerInfo.mode;
  document.getElementById("name").value = customerInfo.name;
  document.getElementById("family").value = customerInfo.family;
  document.getElementById("phone").value = customerInfo.phone;
  document.getElementById("phone").setAttribute("readOnly", true);
  document.getElementById("car").value = customerInfo.car;
  document.getElementById("address").value = customerInfo.address;
  document.getElementById("customer_name").value = "";
  customer_results.innerHTML = "";
  displayBill();
}

function searchPartNumber(pattern) {
  if (pattern.length > 6) {
    pattern = pattern.replace(/\s/g, "");
    pattern = pattern.replace(/-/g, "");
    pattern = pattern.replace(/_/g, "");

    resultBox.innerHTML = `<tr class=''>
                                        <div class='w-full h-52 flex justify-center items-center'>
                                            <img class=' block w-10 mx-auto h-auto' src='./public/img/loading.png' alt='google'>
                                        </div>
                                    </tr>`;
    var params = new URLSearchParams();
    params.append("partNumber", pattern);

    axios
      .post("./app/Controllers/BillController.php", params)
      .then(function (response) {
        const data = response.data;
        if (response.data.length > 0) {
          resultBox.innerHTML = createPartNumberTemplate(data);
        } else {
          resultBox.innerHTML = `<div class="w-full shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border bg-gray-800">
                        <div class="w-full py-3 flex justify-between items-center">      
                            <p class="text-md font-semibold text-white">
                                  کد مد نظر شما موجود نیست.
                            </p>
                        </div>
                    </div>`;
        }
      })
      .catch(function (error) {
        console.log(error);
      });
  } else {
    resultBox.innerHTML = "";
  }
}

function searchInStock(pattern) {
  if (pattern.length > 6) {
    pattern = pattern.replace(/\s/g, "");
    pattern = pattern.replace(/-/g, "");
    pattern = pattern.replace(/_/g, "");

    stock_result.innerHTML = `<tr class=''>
                                        <div class='w-full h-52 flex justify-center items-center'>
                                            <img class=' block w-10 mx-auto h-auto' src='./public/img/loading.png' alt='google'>
                                        </div>
                                    </tr>`;
    var params = new URLSearchParams();
    params.append("searchInStock", pattern);

    axios
      .post("./app/Controllers/BillController.php", params)
      .then(function (response) {
        const data = response.data;
        if (response.data.length > 0) {
          stock_result.innerHTML = createStockTemplate(data);
        } else {
          stock_result.innerHTML = `<div class="w-full shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border bg-gray-800">
                                                <div class="w-full py-3 flex justify-between items-center">      
                                                    <p class="text-md font-semibold text-white">
                                                        کد مد نظر شما موجود نیست.
                                                    </p>
                                                </div>
                                            </div>`;
        }
      })
      .catch(function (error) {
        console.log(error);
      });
  } else {
    stock_result.innerHTML = "";
  }
}

function createPartNumberTemplate(data) {
  let template = ``;
  for (const item of data) {
    template += `
                    <div id="box-${item.id}" class="w-full shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border bg-gray-800">
                        <div class="w-full py-3 flex justify-between items-center">      
                            <p class="text-md font-semibold text-white">
                                   ${item.partnumber}
                            </p>
                            <p class="text-md text-white">اسم قطعه بعدا اضافه می شود</p>
                        </div>
                        <div class="w-full flex justify-between items-center">
                                <input type="number" onkeyup="updateCredential('data-price',${item.id},this.value)" class="ml-2 p-2 w-1/2 d-inline text-md text-white border border-2 placeholder:text-white bg-gray-800" placeholder="قیمت" />
                                <input type="number" onkeyup="updateCredential('data-quantity',${item.id},this.value)" class="ml-2 p-2 w-1/2 d-inline text-md text-white border border-2 placeholder:text-white bg-gray-800" placeholder="تعداد" />
                            <i id="${item.id}"
                                data-quantity= "0"
                                data-price= "0"
                                data-partNumber = "${item.partnumber}"
                                data-name = "بعدا اضافه می شود"
                                onclick="selectGood(this)"
                                    class="material-icons bg-green-600 cursor-pointer rounded-circle hover:bg-green-800 text-white">add
                            </i>
                        </div>
                        <div class="w-full h-6 flex justify-between items-center">
                            <p id="error-${item.id}" class="d-none text-md text-red-600 pt-3">
                            انتخاب قیمت بیشتر از موجودی امکان پذیر نمی باشد
                            </p>
                        </div>
                    </div>
                    `;
  }

  return template;
}

function createStockTemplate(data) {
  let template = ``;
  for (const item of data) {
    template += `
                    <div id="box-${item.id}" class="w-full shadow-md hover:shadow-lg rounded-md px-4 py-3 mb-2 border bg-gray-800">
                        <div class="w-full py-3 flex justify-between items-center">      
                            <p class="text-md font-semibold text-white">
                                ${item.partnumber}
                            </p>
                            <p class="text-md font-semibold text-white">
                            برند : 
                                ${item.brand_name}
                            </p>
                            <p class="text-md font-semibold text-white">
                            موجودی :‌  
                                ${item.existing}
                            </p>
                            <p class="text-md text-white">اسم قطعه بعدا اضافه می شود</p>
                        </div>
                        <div class="w-full flex justify-between items-center">
                                <input type="number" onkeyup="updateCredential('data-price',${item.id},this.value)" class="ml-2 p-2 w-1/2 d-inline text-md text-white border border-2 placeholder:text-white bg-gray-800" placeholder="قیمت" />
                                <input type="number" onkeyup="checkExisting(this, ${item.existing},${item.id});updateCredential('data-quantity',${item.id},this.value)" class="ml-2 p-2 w-1/2 d-inline text-md text-white border border-2 placeholder:text-white bg-gray-800" placeholder="تعداد" />
                            <i id="${item.id}"
                                data-quantity= "0"
                                data-price= "0"
                                data-partNumber = "${item.partnumber}"
                                data-name = "بعدا اضافه می شود"
                                data-max = "${item.existing}"
                                onclick="selectGood(this)"
                                    class="material-icons bg-green-600 cursor-pointer rounded-circle hover:bg-green-800 text-white">add
                            </i>
                        </div>
                        <div class="w-full h-6 flex justify-between items-center">
                        <p id="error-${item.id}" class="d-none text-md text-red-600 pt-3">
                        انتخاب قیمت بیشتر از موجودی امکان پذیر نمی باشد
                        </p>
                        </div>
                    </div>
                    `;
  }

  return template;
}

function checkExisting(element, max, specidier) {
  if (element.value > max) {
    element.value = max;
    document.getElementById("error-" + specidier).classList.toggle("d-none");
    document.getElementById("error-" + specidier).innerHTML =
      "انتخاب مقدار بیشتر از موجودی امکان پذیر نیست.";

    setTimeout(() => {
      document.getElementById("error-" + specidier).classList.toggle("d-none");
    }, 2000);
  }
}

function updateCredential(property, specifier, value) {
  document.getElementById(specifier).setAttribute(property, value);
}

function selectGood(element) {
  const id = element.getAttribute("id");
  const name = element.getAttribute("data-name");
  const price = element.getAttribute("data-price");
  const quantity = element.getAttribute("data-quantity");
  const partNumber = element.getAttribute("data-partNumber");
  const max = element.getAttribute("data-max") ?? "undefined";

  if (price <= 0 || quantity <= 0) {
    document.getElementById("error-" + id).classList.toggle("d-none");
    document.getElementById("error-" + id).innerHTML =
      "لطفا مقادیر وارده را درست بررسی نمایید";

    setTimeout(() => {
      document.getElementById("error-" + id).classList.toggle("d-none");
    }, 2000);

    return false;
  }

  billItems.push({
    id,
    partName: name,
    price_per: price,
    quantity,
    max,
    partNumber,
  });
  document.getElementById("box-" + id).style.display = "none";
  displayBill();
}
