function updateUserAuthority(element) {
  const user = element.getAttribute("data-user");
  const authority = element.getAttribute("data-authority");
  const isChecked = element.checked;

  const params = new URLSearchParams();
  params.append("operation", "update");
  params.append("user", user);
  params.append("authority", authority);
  params.append("isChecked", isChecked);

  axios
    .post("./app/Controllers/UserManagementControllerAjax.php", params)
    .then(function (response) {
      console.log(response.data);
    })
    .catch(function (error) {
      console.log(error.message);
    });
}

function deleteUser(element) {
  const user = element.getAttribute("data-user");
  const confirmedDelete = confirm("Are you sure you want to delete");

  if (!confirmedDelete) {
    return false;
  }

  element.closest("tr").remove();
}
