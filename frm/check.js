$(document).ready(function () {
  function checkUsername() {
    var username = $("#username").val();
    $.ajax({
      type: "POST",
      url: "check.php",
      data: {
        check_username: username,
      },
      success: function (response) {
        if (response > 0) {
          $("#username-error").text("Username already exists");
        } else {
          $("#username-error").text("");
        }
      },
    });
  }

  function checkEmail() {
    var email = $("#email").val();
    $.ajax({
      type: "POST",
      url: "check.php",
      data: {
        check_email: email,
      },
      success: function (response) {
        if (response > 0) {
          $("#email-error").text("Email already exists");
        } else {
          $("#email-error").text("");
        }
      },
    });
  }

  function checkPassword() {
    var password = $("#passwordInput").val();
    var confirmPassword = $("#confirmPasswordInput").val();

    if (password.length < 5 || password.length > 20) {
      $("#password-error").text("Password must be between 5 and 20 characters");
    } else {
      $("#password-error").text("");
    }

    if (password !== confirmPassword) {
      $("#confirmPassword-error").text("Passwords do not match");
    } else {
      $("#confirmPassword-error").text("");
    }
  }

  $("#username").on("blur", checkUsername);
  $("#email").on("blur", checkEmail);

  $("#passwordInput").on("blur", function () {
    checkPassword();
  });

  $("#confirmPasswordInput").on("blur", function () {
    checkPassword();
  });
  
  $("button[name='signup']").on("click", function (event) {
    // Check if there are any error messages present
    if (
      $("#username-error").text() !== "" ||
      $("#email-error").text() !== "" ||
      $("#password-error").text() !== "" ||
      $("#confirmPassword-error").text() !== ""
    ) {
      event.preventDefault(); 
      alert("Please fix the errors before registering.");
    }
  });
});
