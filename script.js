document.getElementById("phone").addEventListener("focus", function () {
  if (this.value === "") {
    this.value = "+7";
  }
});

document.getElementById("phone").addEventListener("input", function (event) {
  const regex = /^\+7\d{0,10}$/;
  if (!regex.test(event.target.value)) {
    this.value = this.value.slice(0, -1);
  }
});

function validateForm() {
  let isValid = true;

  // Валидация имени
  const name = document.getElementById("name").value.trim();
  if (name === "") {
    document.getElementById("nameError").style.display = "block";
    document.getElementById("name").classList.add("error");
    isValid = false;
  } else {
    document.getElementById("nameError").style.display = "none";
    document.getElementById("name").classList.remove("error");
  }

  // Валидация email
  const email = document.getElementById("email").value.trim();
  if (!email.includes("@")) {
    document.getElementById("emailError").style.display = "block";
    document.getElementById("email").classList.add("error");
    isValid = false;
  } else {
    document.getElementById("emailError").style.display = "none";
    document.getElementById("email").classList.remove("error");
  }

  // Валидация телефона
  const phone = document.getElementById("phone").value.trim();
  const phoneRegex = /^\+7\d{10}$/;
  if (!phoneRegex.test(phone)) {
    document.getElementById("phoneError").style.display = "block";
    document.getElementById("phone").classList.add("error");
    isValid = false;
  } else {
    document.getElementById("phoneError").style.display = "none";
    document.getElementById("phone").classList.remove("error");
  }

  return isValid;
}

document
  .getElementById("applicationForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    if (validateForm()) {
      const formData = new FormData(this);

      fetch("form.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            document.getElementById("applicationForm").style.display = "none";
            document.getElementById("successMessage").style.display = "block";
          } else {
            document.getElementById("formErrorMessage").textContent =
              data.message;
            document.getElementById("formErrorMessage").style.display = "block";
          }
        })
        .catch((error) => {
          console.error("Ошибка:", error);
          document.getElementById("formErrorMessage").style.display = "block";
        });
    }
  });
