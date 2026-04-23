document.getElementById('resetForm').addEventListener('submit', e => {
  e.preventDefault();
  const p1 = document.getElementById('password').value;
  const p2 = document.getElementById('password2').value;
  if (p1 !== p2) return alert('Passwords do not match');
  // Demo: pretend to set password
  alert('Password is reset successfully!');
});