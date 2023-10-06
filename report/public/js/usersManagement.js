function updateUserAuthority(element) {
  const user = element.getAttribute("data-user");
  const authority = element.getAttribute("data-authority");
  const isChecked = element.checked;

  alert(authority);
}

function deleteUser(element) {
  const user = element.getAttribute("data-user");
  const confirmedDelete = confirm("Are you sure you want to delete");

  if (!confirmedDelete) {
    return false;
  }

  element.closest("tr").remove();
}
