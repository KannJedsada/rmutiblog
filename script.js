// Count comments using JavaScript
var commentCount = document.querySelectorAll(
  "#comment-section .comment"
).length;

// Display the comment count on the same line
var commentCountElement = document.createElement("div");
commentCountElement.innerHTML = "Comments: " + commentCount;
document.getElementById("comment-section").prepend(commentCountElement);

// Clear search
const input = document.querySelector(".clear-input");
const clearButton = document.querySelector(".clear-input-button");

const handleInputChange = (e) => {
  if (e.target.value && !input.classList.contains("clear-input--touched")) {
    input.classList.add("clear-input--touched");
  } else if (
    !e.target.value &&
    input.classList.contains("clear-input--touched")
  ) {
    input.classList.remove("clear-input--touched");
  }
};

const handleButtonClick = (e) => {
  input.value = "";
  input.focus();
  input.classList.remove("clear-input--touched");
};

clearButton.addEventListener("click", handleButtonClick);
input.addEventListener("input", handleInputChange);

// Show password register
function showPassword() {
  var passwordInput = document.getElementById("passwordInput");
  var confirmPasswordInput = document.getElementById("confirmPasswordInput");

  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    confirmPasswordInput.type = "text";
  } else {
    passwordInput.type = "password";
    confirmPasswordInput.type = "password";
  }
}

// เมนู dropdown
function myFunction() {
  var dropdown = document.getElementById("myDropdown");
  dropdown.classList.toggle("show");
}

document.addEventListener("click", function (event) {
  var dropdowns = document.getElementsByClassName("dropdown-content");
  for (var i = 0; i < dropdowns.length; i++) {
    var openDropdown = dropdowns[i];
    if (
      openDropdown.classList.contains("show") &&
      !event.target.matches(".dropbtn")
    ) {
      openDropdown.classList.remove("show");
    }
  }
});

// ป๊อปอัพเพิ่มโพสต์
function openModal(modalId) {
  var modal = document.getElementById(modalId);
  modal.style.display = "block";

  // Add an event listener to close the modal if clicked outside its content
  window.addEventListener("click", function (event) {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });
}

function closeModal(modalId) {
  var modal = document.getElementById(modalId);
  modal.style.display = "none";
}

// ดูตัวอย่างรูปภาพโพสต์
function previewImage() {
  var input = document.getElementById("postImg");
  var preview = document.getElementById("previewImg");

  preview.style.display = "block";

  var reader = new FileReader();
  reader.onload = function (e) {
    preview.src = e.target.result;
  };

  reader.readAsDataURL(input.files[0]);
}
