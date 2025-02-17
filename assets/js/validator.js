class FormValidator {
  constructor(form) {
    this.form = form;
    this.errorMessage = "Invalid input";
  }

  validate() {
    // Clear previous errors
    this.clearErrors();

    // Get all input fields within the form
    const inputs = this.form.querySelectorAll("input");
    let isValid = true;

    inputs.forEach((input) => {
      const { required, type } = input;
      let valid = true;

      // Check for required fields
      if (required && input.value.trim() === "") {
        this.addError(input, "กรุณากรอกข้อมูลให้ครบถ้วน");
        valid = false;
      }

      // Check for numeric-only fields
      if (input.hasAttribute("number") && !/^\d+$/.test(input.value)) {
        this.addError(input, "ตัวเลขเท่านั้น");
        valid = false;
      }

      // Check for email fields
      if (input.hasAttribute("email") && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value)) {
        this.addError(input, "กรุณากรอกอีเมลให้ถูกต้อง");
        valid = false;
      }

      if (!valid) {
        input.classList.add("invalid");
        isValid = false;
      }
    });

    return isValid;
  }

  addError(input, message) {
    const error = document.createElement("p");
    error.className = "error";
    error.textContent = message;
    input.insertAdjacentElement("afterend", error);
  }

  clearErrors() {
    // Remove all error messages
    const errors = this.form.querySelectorAll(".error");
    errors.forEach((error) => error.remove());

    // Remove invalid class from inputs
    const invalidInputs = this.form.querySelectorAll(".invalid");
    invalidInputs.forEach((input) => input.classList.remove("invalid"));
  }
}