document.getElementById('myForm').addEventListener('submit', function (e) {
  e.preventDefault();

  // Reset error messages
  document.getElementById('emailError').textContent = '';
  document.getElementById('batchError').textContent = '';
  document.getElementById('hobbyError').textContent = '';
  document.getElementById('passwordError').textContent = '';
  document.getElementById('confirmError').textContent = '';

  let valid = true;

  // Email validation
  const email = document.getElementById('email').value.trim();
  const emailPattern = /^\w+\d{2}-\d{4}@diu\.edu\.bd$/;
  if (!emailPattern.test(email)) {
    document.getElementById('emailError').textContent = 'Email must be saha15-5809@diu.edu.bd format';
    valid = false;
  }

  // Batch & Section validation
  const batch = document.getElementById('batch').value.trim();
  const batchPattern = /^\d{2}_[A-Z]$/;
  if (!batchPattern.test(batch)) {
    document.getElementById('batchError').textContent = 'Format must be like 61_J';
    valid = false;
  }

  // Hobby validation
  const hobbyInput = document.getElementById('hobby').value.trim().toLowerCase();
  const hobbies = hobbyInput.split(',').map(h => h.trim()).filter(h => h);

  if (!hobbies.includes('painting')) {
    document.getElementById('hobbyError').textContent = 'Hobby list must include "Painting"';
    valid = false;
  } else if (hobbies.length != 5) {
    document.getElementById('hobbyError').textContent = 'You must enter at least 5 hobbies (comma-separated)';
    valid = false;
  } else if (hobbyInput.includes(' ') && !hobbyInput.includes(',')) {
    document.getElementById('hobbyError').textContent = 'Separate multiple hobbies using commas';
    valid = false;
  }

  // Password validation
  const password = document.getElementById('password').value;
  const passwordPattern = /^[A-Za-z#^&]{6,}$/;
  if (!passwordPattern.test(password)) {
    document.getElementById('passwordError').textContent = 'Password not valid';
    valid = false;
  }

  // Confirm Password
  const confirmPassword = document.getElementById('confirmPassword').value;
  if (confirmPassword !== password) {
    document.getElementById('confirmError').textContent = 'Passwords do not match';
    valid = false;
  }

  if (valid) {
    alert('Form submitted successfully!');
    // You can submit the form here if needed
  }
});
