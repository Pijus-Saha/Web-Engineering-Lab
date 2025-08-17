function validateForm() {
  const email = document.querySelector('input[name="email"]').value;
  const password = document.querySelector('input[name="password"]').value;
  const dob = document.querySelector('input[name="dob"]').value;

  const passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[@_]).{6,}$/;
  const dobPattern = /^\d{2}-\d{2}-\d{4}$/;

  if (!dobPattern.test(dob)) {
    alert("Date of Birth must be in dd-mm-yyyy format.");
    return false;
  }

  if (!email.endsWith(".cse@diu.edu.bd")) {
    alert("Email must end with .cse@diu.edu.bd");
    return false;
  }

  if (!passwordPattern.test(password)) {
    alert("Password must be at least 6 characters and include A-Z, a-z, @ or _");
    return false;
  }

  return true;
}
