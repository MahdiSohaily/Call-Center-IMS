let bill_number = null;
function getBillData() {
  var params = new URLSearchParams();
  params.append("getFactorNumber", "getFactorNumber");
  axios
    .post("./app/Controllers/BillController.php", params)
    .then(function (response) {
      bill_number = response.data;
      BillInfo.billNO = bill_number;
      previewBill();
      displayCustomer();
      displayBillDetails();
    })
    .catch(function (error) {
      console.log(error);
    });
}

function previewBill() {
  let counter = 1;
  let template = ``;
  let totalPrice = 0;

  for (const item of billItems) {
    const payPrice = Number(item.quantity) * Number(item.price_per);
    totalPrice += payPrice;

    template += `
            <tr style="padding: 10px !important;" class="even:bg-gray-100">
                <td class="text-sm">
                    <span>${counter}</span>
                </td>
                <td class="text-sm">
                    <span>${item.partName}</span>
                </td>
                <td class="text-sm">
                    <span>${item.quantity}</span>
                </td>
                <td class="text-sm">
                    <span>${formatAsMoney(Number(item.price_per))}</span>
                </td>
                <td class="text-sm">
                    <span>${formatAsMoney(payPrice)}</span>
                </td>
            </tr> `;
    counter++;
  }
  bill_body_result.innerHTML = template;
}

function formatAsMoney(number) {
  return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " ریال";
}

function displayCustomer() {
  document.getElementById("name").innerHTML =
    customerInfo.name + " " + customerInfo.family ?? "";
  document.getElementById("phone").innerHTML = customerInfo.phone;
  // document.getElementById('car').innerHTML = customerInfo.car;
  // document.getElementById('address').innerHTML = customerInfo.address;
}

function displayBillDetails() {
  // document.getElementById("billNO2").innerHTML = BillInfo.billNO;
  document.getElementById("date").innerHTML = BillInfo.date;
  document.getElementById("quantity2").value = BillInfo.quantity;
  document.getElementById("totalPrice2").value = formatAsMoney(
    BillInfo.totalPrice
  );
  document.getElementById("discount").value = BillInfo.discount;
  document.getElementById("tax").value = BillInfo.tax;
  document.getElementById("total_in_word2").innerHTML = BillInfo.totalInWords;
}
