$(document).ready(function () {
  $(".callinfobox-option div").click(function () {
    var txt = $.trim($(this).text());
    var box = $(".callinfo");
    box.val(box.val() + txt);
  });
});

$(document).ready(function () {
  $(".edit-shomare-faktor-btn").click(function () {
    $(".modal").css("display", "block");
    $(this).attr("id");
    $("iframe").attr(
      "src",
      "shomare-faktor-edit-page.php?q=" + $(this).attr("id")
    );
  });
  $(".e-f-userlist").val($(".e-f-userlist").attr("data")).change();
});

$(document).ready(function () {
  // Get the modal
  var modal = document.getElementById("myModal");

  // Get the button that opens the modal
  var btn = document.getElementById("myBtn");

  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("close")[0];

  // When the user clicks on <span> (x), close the modal
  span.onclick = function () {
    modal.style.display = "none";
    window.location.reload();
  };

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
      window.location.reload();
    }
  };
});

$(document).ready(function () {
  $(".manual-add-customer a").click(function () {
    var x = $(".manual-add-customer div").text();
    $(".manual-add-customer a").attr("href", "main.php?phone=" + x);
  });
});
function updateBill(element) {
  $(".modal").css("display", "block");
  const id = element.getAttribute("id");
  $("iframe").attr("src", "shomare-faktor-edit-page.php?q=" + id);
  $(".e-f-userlist").val($(".e-f-userlist").attr("data")).change();
}
