// Get all elements with the class "dropdown-btn"
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

// Loop through the buttons and add the click event
for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
    // Toggle between adding and removing the "active" class
    this.classList.toggle("active");

    // Toggle the display of the dropdown container
    var dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  });
}
