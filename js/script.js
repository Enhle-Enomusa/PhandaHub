// js/script.js - client-side validation helpers
// Defensive front-end validation. Server still re-validates everything.

document.addEventListener('DOMContentLoaded', () => {

  // Force numeric-only on any input with data-numeric attribute (e.g. phone).
  document.querySelectorAll('input[data-numeric]').forEach(input => {
    input.addEventListener('input', () => {
      input.value = input.value.replace(/[^0-9]/g, '');
    });
  });

  // Register form validation
  const reg = document.getElementById('registerForm');
  if (reg) {
    reg.addEventListener('submit', (e) => {
      const name  = reg.full_name.value.trim();
      const email = reg.email.value.trim();
      const phone = reg.phone.value.trim();
      const pass  = reg.password.value;
      const conf  = reg.confirm.value;

      if (!name || !email || !phone || !pass) {
        e.preventDefault(); alert('All fields are required.'); return;
      }
      if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) {
        e.preventDefault(); alert('Please enter a valid email.'); return;
      }
      if (!/^[0-9]{10}$/.test(phone)) {
        e.preventDefault(); alert('Phone must be exactly 10 digits.'); return;
      }
      if (pass.length < 6) {
        e.preventDefault(); alert('Password must be at least 6 characters.'); return;
      }
      if (pass !== conf) {
        e.preventDefault(); alert('Passwords do not match.'); return;
      }
    });
  }

  // Login form validation
  const login = document.getElementById('loginForm');
  if (login) {
    login.addEventListener('submit', (e) => {
      if (!login.email.value.trim() || !login.password.value) {
        e.preventDefault(); alert('Please fill in both email and password.');
      }
    });
  }

  // Sell product validation
  const sell = document.getElementById('sellForm');
  if (sell) {
    sell.addEventListener('submit', (e) => {
      if (!sell.title.value.trim() || !sell.description.value.trim() ||
          !sell.price.value || !sell.category.value) {
        e.preventDefault(); alert('Please fill in all listing fields.'); return;
      }
      if (parseFloat(sell.price.value) <= 0) {
        e.preventDefault(); alert('Price must be greater than zero.');
      }
    });
  }

  // Fake payment validation
  const pay = document.getElementById('paymentForm');
  if (pay) {
    // Auto-format card number with spaces every 4 digits.
    const card = pay.card_number;
    card.addEventListener('input', () => {
      let v = card.value.replace(/\D/g,'').slice(0,16);
      card.value = v.replace(/(.{4})/g,'$1 ').trim();
    });
    pay.addEventListener('submit', (e) => {
      const num = pay.card_number.value.replace(/\s/g,'');
      if (!/^[0-9]{16}$/.test(num)) { e.preventDefault(); alert('Card number must be 16 digits.'); return; }
      if (!/^[0-9]{3,4}$/.test(pay.cvv.value)) { e.preventDefault(); alert('Invalid CVV.'); return; }
      if (!pay.expiry.value || !pay.holder.value.trim()) { e.preventDefault(); alert('All payment fields are required.'); }
    });
  }
});
