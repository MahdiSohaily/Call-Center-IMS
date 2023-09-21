// Global controllers for operations messages
const form_success = document.getElementById("form_success");
const form_error = document.getElementById("form_error");
// Global price variable
let price = null;

// A function to update the global price while typing in the input feild
function update_price(element) {
  price = element.value;
  const partNumber = element.getAttribute("data-code").split("-")[0];

  const targetRelation = element.getAttribute("data-target");

  // Step 2: Select all elements with the same data-relation attribute
  const elementsWithSameDataRelation = document.querySelectorAll(
    `[data-relation="${targetRelation}"]`
  );

  // Step 3: Iterate through the selected elements and update their innerHTML
  elementsWithSameDataRelation.forEach((element) => {
    // Update the innerHTML as needed
    element.innerHTML = price; // Replace with your desired content
  });
}

// A function to set the price to we don't have
function donotHave(element) {
  element.disabled = true;
  element.innerHTML = "ثبت شد";

  price = "موجود نیست";
  part = element.getAttribute("data-part");
  const input = document.getElementById(part + "-price");
  input.value = price;
  const partNumber = element.getAttribute("data-code").split("-")[0];
  const target = document.getElementById(partNumber + "-append");
  target.innerHTML = price;
  createRelation(element, "not");
  input.value = null;
}

// A function to send a request in order to ask the price for specific code
function askPrice(element) {
  element.disabled = true;
  element.innerHTML = "ارسال شد";

  setTimeout(() => {
    element.disabled = false;
    element.innerHTML = `
        ارسال به نیایش
        <i class="material-icons text-green-500 font-sm px-1">check_circle</i>
        `;
  }, 5000);

  // Accessing the form fields to get thier value for an ajax store operation
  const partNumber = element.getAttribute("data-part");
  const user_id = element.getAttribute("data-user");
  const customer_id = document.getElementById("customer_id").value;

  const params = new URLSearchParams();
  params.append("askPrice", "askPrice");
  params.append("partNumber", partNumber);
  params.append("customer_id", customer_id);
  params.append("user_id", user_id);

  axios
    .post("./app/Controllers/GivenPriceAjax.php", params)
    .then(function (response) {
      if (response.data == true) {
        form_success.style.bottom = "10px";
        setTimeout(() => {
          form_success.style.bottom = "-300px";
        }, 2000);
      } else {
        form_error.style.bottom = "10px";
        setTimeout(() => {
          form_error.style.bottom = "-300px";
        }, 2000);
      }
    })
    .catch(function (error) {});
}

// A function to create the relationship
function createRelation(e, button = null) {
  e.disabled = true;
  if (button) {
    setTimeout(() => {
      e.disabled = false;
      e.innerHTML = `
        موجود نیست
        <i class="material-icons text-green-500 font-sm px-1">check_circle</i>
        `;
    }, 5000);
  } else {
    setTimeout(() => {
      e.disabled = false;
      e.innerHTML = `
            ثبت قیمت
            <i class="material-icons text-green-500 font-sm px-1">check_circle</i>`;
    }, 5000);
  }

  // Accessing the form fields to get thier value for an ajax store operation
  const partNumber = e.getAttribute("data-part");
  const customer_id = document.getElementById("customer_id").value;
  const notification_id = document.getElementById("notification_id").value;
  const code = e.getAttribute("data-code");

  const goodPrice = document.getElementById(partNumber + "-price").value;
  const resultBox = document.getElementById("price-" + partNumber);

  // Defining a params instance to be attached to the axios request
  const params = new URLSearchParams();
  params.append("store_price", "store_price");
  params.append("partNumber", partNumber);
  params.append("customer_id", customer_id);
  params.append("notification_id", notification_id);
  params.append("price", goodPrice);
  params.append("code", code);

  axios
    .post("./app/Controllers/GivenPriceAjax.php", params)
    .then(function (response) {
      if (response.data) {
        form_success.style.bottom = "10px";
        goodPrice.value = null;
        setTimeout(() => {
          form_success.style.bottom = "-300px";
          resultBox.innerHTML = response.data;
        }, 2000);
      } else {
        form_error.style.bottom = "10px";
        setTimeout(() => {
          form_error.style.bottom = "-300px";
          location.reload();
        }, 2000);
      }
    })
    .catch(function (error) {});
}

// A function to set the price while clicking on the prices table
function setPrice(element) {
  newPrice = element.getAttribute("data-price");
  part = element.getAttribute("data-part");
  const input = document.getElementById(part + "-price");
  input.value = newPrice;
  price = newPrice;

  const targetRelation = element.getAttribute("data-target");

  // Step 2: Select all elements with the same data-relation attribute
  const elementsWithSameDataRelation = document.querySelectorAll(
    `[data-relation="${targetRelation}"]`
  );

  // Step 3: Iterate through the selected elements and update their innerHTML
  elementsWithSameDataRelation.forEach((element) => {
    // Update the innerHTML as needed
    element.innerHTML = price; // Replace with your desired content
  });
}

// A function to copy content to cliboard
function copyPrice(elem) {
  // Get the text field
  let parentElement = document.getElementById("priceReport");

  let tdElements = parentElement.getElementsByTagName("td");
  let tdTextContent = [];

  const elementLength = tdElements.length;

  const dash = ["موجود نیست", "نیاز به بررسی"];
  const space = ["کد اشتباه", "نیاز به قیمت"];

  for (let i = 0; i < elementLength; i++) {
    if (tdElements[i].textContent.trim() !== "content_copy") {
      let text = "";
      if (dash.includes(tdElements[i].textContent.trim())) {
        text = "-";
      } else if (space.includes(tdElements[i].textContent.trim())) {
        text = " ";
      } else {
        text = tdElements[i].textContent.trim();
      }

      tdTextContent.push(text);
    }
  }

  const chunkSize = 2;
  tdTextContent = tdTextContent.filter((td) => td.length > 0);

  let finalResult = [];
  const size = tdTextContent.length;
  for (let i = 0; i < size; i += chunkSize) {
    finalResult.push(tdTextContent.slice(i, i + chunkSize));
  }

  // Copy the text inside the text field

  let text = "";
  for (let item of finalResult) {
    text += item.join(" : ");
    text += "\n";
  }
  copyToClipboard(text.trim());

  // Alert the copied text
  elem.innerHTML = `done`;
  setTimeout(() => {
    elem.innerHTML = `content_copy`;
  }, 1500);
}

function copyItemPrice(elem) {
  // Get the parent <td> element
  var parentTd = elem.parentNode;

  // Get the siblings <td> elements
  var sibling1 = parentTd.previousElementSibling;
  var sibling2 = sibling1.previousElementSibling;

  // Retrieve the innerHTML of the sibling <td> elements
  var sibling1HTML = sibling1.firstElementChild.innerHTML;
  var sibling2HTML = sibling2.innerHTML;

  const dash = ["موجود نیست", "نیاز به بررسی"];
  const space = ["کد اشتباه", "نیاز به قیمت"];

  let value = "";
  if (dash.includes(sibling1HTML)) {
    value = "-";
  } else if (space.includes(sibling1HTML)) {
    value = " ";
  } else {
    value = sibling1HTML;
  }

  let text = sibling2HTML + " : " + value;

  copyToClipboard(text);

  // Alert the copied text
  elem.innerHTML = `done`;
  setTimeout(() => {
    elem.innerHTML = `content_copy`;
  }, 1500);
}

function deleteGivenPrice(element) {
  const partNumber = element.getAttribute("data-part");
  const id = element.getAttribute("data-del");

  // Accessing the form fields to get thier value for an ajax store operation
  const customer_id = document.getElementById("customer_id").value;
  const notification_id = document.getElementById("notification_id").value;
  const code = element.getAttribute("data-code");
  const resultBox = document.getElementById("price-" + partNumber);
  // Defining a params instance to be attached to the axios request
  const params = new URLSearchParams();
  params.append("delete_price", "delete_price");
  params.append("partNumber", partNumber);
  params.append("customer_id", customer_id);
  params.append("notification_id", notification_id);
  params.append("code", code);
  params.append("id", id);

  axios
    .post("./app/Controllers/deleteGivenPrice.php", params)
    .then(function (response) {
      if (response.data) {
        console.log(response.data);
        resultBox.innerHTML = response.data;
      } else {
        console.log(response.data);
      }
    })
    .catch(function (error) {});
}

function closeTab() {
  // Set up a timeout to close the tab after 2 minutes (120,000 milliseconds)
  setTimeout(function () {
    // Try to close the window (tab)
    // This may not work if the window was not opened by a script or if the browser blocks the action.
    window.close();
  }, 60000);
}

function appendBrand(element) {
  const brand = element.getAttribute("data-brand");
  const part = element.getAttribute("data-part");
  const input = document.getElementById(part + "-price");
  input.value += " " + brand;

  const targetRelation = element.getAttribute("data-target");

  // Step 2: Select all elements with the same data-relation attribute
  const elementsWithSameDataRelation = document.querySelectorAll(
    `[data-relation="${targetRelation}"]`
  );

  // Step 3: Iterate through the selected elements and update their innerHTML
  elementsWithSameDataRelation.forEach((element) => {
    // Update the innerHTML as needed
    element.innerHTML += " " + brand; // Replace with your desired content
  });
}

document.addEventListener("DOMContentLoaded", function () {
  const accordionHeaders = document.querySelectorAll(".accordion-header");

  accordionHeaders.forEach((header) => {
    const content = header.nextElementSibling;

    header.addEventListener("click", function () {
      if (content.style.maxHeight !== "1000vh") {
        content.style = "max-height:1000vh;";
      } else {
        content.style = "max-height:0vh;";
      }
    });
  });
});

function telegram(e) {
  console.log("here we are");
  setTimeout(() => {
    e.disabled = false;
    e.innerHTML = `
            ثبت قیمت
            <i class="material-icons text-green-500 font-sm px-1">check_circle</i>`;
  }, 5000);

  // Accessing the form fields to get thier value for an ajax store operation
  const partNumber = e.getAttribute("data-part");
  const customer_id = e.getAttribute("data-customer");
  const notification_id = document.getElementById("notification_id").value;
  const code = e.getAttribute("data-code");

  const goodPrice = document.getElementById(partNumber + "-price").value;
  const resultBox = document.getElementById("price-" + partNumber);

  // Defining a params instance to be attached to the axios request
  const params = new URLSearchParams();
  params.append("store_price", "store_price");
  params.append("partNumber", partNumber);
  params.append("customer_id", 2);
  params.append("notification_id", notification_id);
  params.append("price", goodPrice);
  params.append("code", code);

  axios
    .post("./app/Controllers/GivenPriceAjax.php", params)
    .then(function (response) {
      if (response.data) {
        form_success.style.bottom = "10px";
        goodPrice.value = null;
        setTimeout(() => {
          form_success.style.bottom = "-300px";
          resultBox.innerHTML = response.data;
        }, 2000);
      } else {
        form_error.style.bottom = "10px";
        setTimeout(() => {
          form_error.style.bottom = "-300px";
          location.reload();
        }, 2000);
      }
    })
    .catch(function (error) {});
  sendMessage(customer_id, code, goodPrice);
}

function sendMessage(receiver, code, price) {
  // Defining a params instance to be attached to the axios request
  const params = new URLSearchParams();
  params.append("sendMessage", "sendMessage");
  params.append("receiver", receiver);
  params.append("code", code);
  params.append("price", price);

  axios
    .post("http://telegram.om-dienstleistungen.de/", params)
    .then(function (response) {
      if (response.data) {
        console.log(response.data);
      } else {
      }
    })
    .catch(function (error) {});
}

function onScreen(element) {
  const section = document.getElementById(element.getAttribute("data-move"));
  window.scrollTo(0, section.getBoundingClientRect().top - 70);
}

const elementsWithDataTarget = document.querySelectorAll("[data-relation]");

// Get all elements with the data-relation attribute
const elementsWithDataRelation = document.querySelectorAll("[data-relation]");

// Create an object to store the HTML content by relation
const htmlByRelation = {};

// Loop through the selected elements
elementsWithDataRelation.forEach((element) => {
  const relation = element.getAttribute("data-relation");
  const innerHTML = element.innerHTML;

  if (!htmlByRelation[relation]) {
    // Store the inner HTML of the first element in the group
    htmlByRelation[relation] = innerHTML;
  } else {
    // Set the inner HTML of subsequent elements in the group
    element.innerHTML = htmlByRelation[relation];
  }
});
