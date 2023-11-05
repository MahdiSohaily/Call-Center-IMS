let localData = [];
function getInitialData() {
  fetchLocalPartnersData().then(function (data) {
    localData = data;
  });
}

getInitialData();

/* ------------------- Related to teh send Message Section   ----------------------- */
function sendMessage() {
  const address = "./app/Controllers/TelegramPartnerControllerAjax.php";
  const message_content = document.getElementById("message_content").value;

  const categories = document.querySelectorAll(".target_partner");
  const data = [];
  const names = [];

  for (const node of categories) {
    const authority = node.getAttribute("data_id");
    const name = node.innerText.split("\n")[0];
    names.push(name);
    data.push(authority);
  }

  const receivers = data.filter((item, index, self) => {
    return self.indexOf(item) === index;
  });

  if (message_content.length > 0 && receivers.length > 0) {
    const params = new URLSearchParams();
    params.append("action", "sendMessage");
    params.append("message_content", message_content);
    params.append("data", JSON.stringify(receivers));

    axios
      .post("http://telegram.yadak.center/", params)
      .then(function (response) {})
      .catch(function (error) {});

    const logParams = new URLSearchParams();
    logParams.append("logAction", "log");
    logParams.append("message_content", message_content);
    logParams.append("receivers", JSON.stringify(names));

    axios
      .post(address, logParams.toString())
      .then(function (response) {
        document.getElementById("message_content").value = null;
        const message = document.getElementById("success");

        const target_partners = document.querySelectorAll(".target_partner");

        const category_identifier = document.querySelectorAll(
          ".category_identifier"
        );
        for (const node of target_partners) {
          node.parentNode.removeChild(node);
        }
        for (const node of category_identifier) {
          node.checked = false;
        }

        message.style.opacity = 1;
        setTimeout(() => {
          message.style.opacity = 0;
        }, 2000);
      })
      .catch(function (error) {
        console.log(error);
        // window.location.reload();
      });
  } else {
    const message = document.getElementById("error");
    message.style.opacity = 1;
    setTimeout(() => {
      message.style.opacity = 0;
    }, 2000);
  }
  // window.location.reload();
}

function updateCategory(element) {
  const address = "./app/Controllers/TelegramPartnerControllerAjax.php";

  const categories = document.querySelectorAll(".category_identifier");
  const data = {};

  for (const node of categories) {
    const category = node.getAttribute("name");
    const isChecked = node.checked;
    data[category] = isChecked;
  }

  for (let brand in data) {
    const category = document.getElementById(brand + "_result");
    category.innerHTML = null;
  }

  const params = new URLSearchParams();
  params.append("getCategories", "getCategories");
  params.append("data", JSON.stringify(data));

  axios
    .post(address, params)
    .then(function (response) {
      const data = response.data;
      for (let brand in data) {
        const category = document.getElementById(brand + "_result");
        category.innerHTML = null;
        for (let item of data[brand]) {
          category.innerHTML += `
                    <span class="text-sm flex items-center target_partner rounded-lg bg-green-700 text-white p-2 my-1 mx-2" data_id ="${
                      item.chat_id
                    }">
                    ${item.name.toLowerCase()}
                    <i class="cursor-pointer material-icons text-red-500 pr-1 text-sm" onclick="removePartner(this)">close</i>
                    </span>`;
        }
      }
      attachPartners(response.data);
    })
    .catch(function (error) {});
}

function removePartner(element) {
  const parentElement = element.parentElement;
  parentElement.remove();
}

function displayLocalData() {
  fetchLocalPartnersData().then(function (data) {
    const initial_data = document.getElementById("initial_data");
    let template = "";
    let counter = 1;
    if (data.length > 0) {
      for (let user of data) {
        template += `
        <tr class="even:bg-indigo-100" 
            data-operation='update'
            data-chat="${user.chat_id}" 
            data-name=" ${user.name}" 
            data-username="${user.username}" 
            data-profile="${user.profile}">
                <td class="p-2 text-center">${counter} </td>
                <td class="p-2 text-center">${user.name}</td>
                <td class="p-2 text-center" style="text-decoration:ltr">${
                  user.username
                }</td>
                <td class="p-2 text-center"> <img class="userImage mx-2 mx-auto d-block" src='${
                  user.profile
                }' /> </td>
                <td class="p-2 text-center"> 
                        <input ${
                          user.honda == 1 ? "checked" : ""
                        } data-section="exist" class="cursor-pointer exist user-${
          user.chat_id
        }" data-user="${
          user.chat_id
        }" type="checkbox" name="honda" onclick="addPartner(this)" /> 
        </td>
                <td class="p-2 text-center">
                <input ${
                  user.kia == 1 ? "checked" : ""
                } data-section="exist" class="cursor-pointer exist user-${
          user.chat_id
        } " data-user="${
          user.chat_id
        }" type="checkbox" name="kia" onclick="addPartner(this)" /> </td>
                <td class="p-2 text-center"> <input ${
                  user.chines == 1 ? "checked" : ""
                } data-section="exist" class="cursor-pointer exist user-${
          user.chat_id
        }" data-user=${
          user.chat_id
        }" type="checkbox" name="chines" onclick="addPartner(this)" /> </td>
        </tr>
        `;
        counter += 1;
      }
    } else {
      template = `<tr>
      <td colspan="7" class="text-center py-3 text-red-500">
      موردی برای نمایش وجود ندارد.
      </td>
      </tr>`;
    }
    initial_data.innerHTML = template;
  });
}

/** ------------------ Related to the Fetching new Contact from the Telegram section --------------- */

const contact = document.getElementById("results_new"); // Result box for displaying data coming from Telegram
let isLoadedTelegramContacts = false; // Whether the contact has been loaded

// Define a function to fetch local partners data
async function fetchLocalPartnersData() {
  const address = "./app/Controllers/TelegramPartnerControllerAjax.php";
  const params = new URLSearchParams();
  params.append("getInitialData", "getInitialData");

  try {
    const response = await axios.post(address, params);
    return response.data;
  } catch (error) {
    console.log(error);
    return null;
  }
}

// Define a function to display the contact data
function displayTelegramData(data) {
  let template = ``;
  let counter = 1;

  fetchLocalPartnersData().then(function (items) {
    // Convert chat_id to integers
    const itemsWithIntChatId = items.map((item) => {
      return {
        ...item,
        chat_id: parseInt(item.chat_id, 10),
      };
    });

    // Create a map to store items by their chat_id for faster lookups
    const itemsMap = new Map(
      itemsWithIntChatId.map((item) => [item.chat_id, item])
    );

    // Merge properties from the items array into the data array
    const mergedData = data.map((item) => {
      const existingItem = itemsMap.get(item.chat_id);
      if (existingItem) {
        // Merge properties from the 'items' array into 'data'
        const mergedItem = { ...item, ...existingItem };
        return mergedItem;
      }
      return item;
    });

    mergedData.forEach(function (user) {
      const isHondaChecked = user.honda === "1";
      const isKiaChecked = user.kia === "1";
      const isChinesChecked = user.chines === "1";
      template += `
        <tr class="even:bg-indigo-100" 
          data-chat="${user.chat_id}"
          data-name="${user.title}"
          data-username="${user.username}"
          data-profile="${user.profile_path}"
          data-operation="check">
          <td class="p-2 text-center">${counter}</td>
          <td class="p-2 text-center">${user.title}</td>
          <td class="p-2 text-center" style="text-decoration:ltr">${
            user.username
          }</td>
          <td class="p-2 text-center"><img class="userImage mx-2 mx-auto d-block" src="${
            user.profile_path
          }" /></td>
          <td class="p-2 text-center">
            <input data-section="telegram" class="cursor-pointer telegram user-${
              user.chat_id
            }" data-user="${user.chat_id}" onclick="addPartner(this)"
              type="checkbox" name="honda" ${isHondaChecked ? "checked" : ""} />
          </td>
          <td class="p-2 text-center">
            <input data-section="telegram" class="cursor-pointer telegram user-${
              user.chat_id
            }" data-user="${user.chat_id}" onclick="addPartner(this)"
              type="checkbox" name="kia" ${isKiaChecked ? "checked" : ""} />
          </td>
          <td class="p-2 text-center">
            <input data-section="telegram" class="cursor-pointer telegram user-${
              user.chat_id
            }" data-user="${user.chat_id}" onclick="addPartner(this)"
              type="checkbox" name="chines" ${
                isChinesChecked ? "checked" : ""
              } />
          </td>
        </tr>`;
      counter += 1;
    });
    // Assuming you have an element with the ID 'contact' to display the data
    const contact = document.getElementById("contact");
    if (contact) {
      contact.innerHTML = template;
    }
  });
}

// Define a function to get and display contacts
async function getContacts() {
  if (!isLoadedTelegramContacts) {
    // Assuming you have an element with the ID 'contact' to display the loading animation
    const contact = document.getElementById("contact");
    if (contact) {
      contact.innerHTML = `
        <tr>
          <td colspan="7" class="py-5">
            <img class='block w-10 mx-auto h-auto' src="./public/img/loading.png" />
          </td>
        </tr>
      `;
    }

    try {
      // Make the Axios request to fetch contact data
      const response = await axios.post("http://telegram.yadak.center/");
      displayTelegramData(response.data);
      isLoadedTelegramContacts = true;
    } catch (error) {
      console.log(error);
      // Display an error message
      if (contact) {
        contact.innerHTML = `
          <tr>
            <td colspan="7" class="py-5">
              <p class="text-center text-bold text-red-500 ">اطلاعاتی دریافت نشد, لطفا لحظاتی بعد تلاش نمایید</p>
            </td>
          </tr>`;
      }
    }
  }
}

function hardRefresh() {
  isLoadedTelegramContacts = false;
  getContacts();
}

function addPartner(element) {
  // the target URL to send the ajax request
  const address = "./app/Controllers/TelegramPartnerControllerAjax.php";

  const closestTr = element.closest("tr");
  const section = element.getAttribute("data-section");

  const chat_id = closestTr.getAttribute("data-chat");
  const name = closestTr.getAttribute("data-name");
  const username = closestTr.getAttribute("data-username");
  const profile = closestTr.getAttribute("data-profile");
  const operation = closestTr.getAttribute("data-operation");

  const authorityList = document.querySelectorAll(
    "." + section + ".user-" + chat_id
  );

  const data = {};

  for (const node of authorityList) {
    const authority = node.getAttribute("name");
    const isChecked = node.checked;
    data[authority] = isChecked;
  }

  const params = new URLSearchParams();
  params.append("operation", operation);
  params.append("chat_id", chat_id);
  params.append("name", name);
  params.append("username", username);
  params.append("profile", profile);
  params.append("data", JSON.stringify(data));

  axios
    .post(address, params)
    .then(function (response) {})
    .catch(function (error) {});
}

function displayCategories() {
  getExistingCategories().then(function (data) {
    let counter = 1;
    let template = "";
    const resultBox = document.getElementById("category_data");
    for (const item of data) {
      template += `
      <tr class="even:bg-indigo-100" data-cat="${item.id}">
        <td class="p-2 text-center text-bold">${counter}</td>
        <td class="p-2 text-center text-bold" id="target-${item.id}">${item.name}</td>
        <td class="p-2 text-center">
        <i data-cat-id="${item.id}" data-value="${item.name}" onclick="editCategory(this)" class="cursor-pointer material-icons font-semibold text-blue-400">edit</i>
        </td>
      </tr>
      `;
      counter += 1;
    }

    resultBox.innerHTML = template;
  });
}

async function getExistingCategories() {
  const address = "./app/Controllers/TelegramPartnerControllerAjax.php";
  const params = new URLSearchParams();
  params.append("getExistingCategories", "getExistingCategories");

  try {
    const response = await axios.post(address, params);
    return response.data;
  } catch (error) {
    console.log(error);
    throw error; // Rethrow the error to be caught by the caller
  }
}

let previous_id = null;

function editCategory(element) {
  const id = element.getAttribute("data-cat-id");
  const value = element.getAttribute("data-value");

  const editForm = document.getElementById("edit_category");
  const saveForm = document.getElementById("save_category");

  if (previous_id !== id) {
    previous_id = id;
    editForm.classList.remove("hidden");
    saveForm.classList.add("hidden");
    document.getElementById("edit_category_name").value = value;
    document.getElementById("category_id").value = id;
  } else {
    if (editForm.classList.contains("hidden")) {
      editForm.classList.remove("hidden");
      document.getElementById("edit_category_name").value = value;
      document.getElementById("category_id").value = id;
      saveForm.classList.add("hidden");
    } else {
      editForm.classList.add("hidden");
      saveForm.classList.remove("hidden");
    }
  }
}

function editCategoryForm() {
  event.preventDefault();
  const id = document.getElementById("category_id").value;
  const value = document.getElementById("edit_category_name").value;
  const address = "./app/Controllers/TelegramPartnerControllerAjax.php";

  const params = new URLSearchParams();
  params.append("editCategory", "editCategory");
  params.append("id", id);
  params.append("value", value);

  try {
    const response = axios.post(address, params).then((response) => {
      document.getElementById("success_edit").style.opacity = 1;
      document.getElementById("target-" + id).innerHTML = value;

      setTimeout(() => {
        document.getElementById("success_edit").style.opacity = 0;
      }, 1000);
    });
  } catch (error) {
    console.log(error);
    return null;
  }
}
