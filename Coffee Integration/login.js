function validateForm() {
  var password = document.getElementById("password").value;
  var confirmPassword = document.getElementById("confirmPassword").value;
  if (password != confirmPassword) {
      document.getElementById("passwordMatchError").style.display = "block";
      return false;
  }
  return true;
}

const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', () => {
  container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
  container.classList.remove("right-panel-active");
});

document.addEventListener("DOMContentLoaded", function () {
  setTimeout(function () {
      var alerts = document.querySelectorAll("div.alert");
      alerts.forEach(function (alert) {
          alert.remove();
      });
  }, 4000); // 4 secs

  var closeButtons = document.getElementsByClassName("closebtn");
  for (var i = 0; i < closeButtons.length; i++) {
      closeButtons[i].onclick = function () {
          var div = this.parentElement;
          div.style.opacity = "0";
          setTimeout(function () { div.style.display = "none"; }, 600);
      }
  }

  document.getElementById('signupForm').addEventListener('submit', function(e) {
      e.preventDefault();
      if (!validateForm()) {
          return;
      }

      var formData = new FormData(this);

      fetch('signup.php', {
          method: 'POST',
          body: formData
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              document.querySelector('.sign-in-container').style.display = 'none';
              document.getElementById('signupForm').style.display = 'none';
              document.getElementById('signupSuccessMessage').style.display = 'block';
          } else {
              document.getElementById('passwordMatchError').textContent = data.message;
              document.getElementById('passwordMatchError').style.display = 'block';
          }
      })
      .catch(error => console.error('Error:', error));
  });
});
