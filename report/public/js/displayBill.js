let bill_number = null;
function getBillData() {
  var params = new URLSearchParams();
  params.append("getFactorNumber", "getFactorNumber");
  axios
    .post("./app/Controllers/BillController.php", params)
    .then(function (response) {
      bill_number = response.data;
      // BillInfo.billNO = bill_number;
      document.getElementById("billNO_bill").innerHTML = BillInfo.billNO;
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
  return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function displayCustomer() {
  document.getElementById("name_bill").innerHTML =
    customerInfo.name + " " + customerInfo.family ?? "";
  document.getElementById("phone_bill").innerHTML = customerInfo.phone;
  document.getElementById("userAddress").innerHTML =
    "نشانی : " + customerInfo.address;
}

function displayBillDetails() {
  document.getElementById("date_bill").innerHTML = BillInfo.date;
  document.getElementById("quantity_bill").value = BillInfo.quantity;
  document.getElementById("totalPrice_bill").value = formatAsMoney(
    BillInfo.totalPrice
  );
  document.getElementById("discount_bill").value = BillInfo.discount;
  document.getElementById("total_in_word_bill").innerHTML =
    BillInfo.totalInWords;
}
