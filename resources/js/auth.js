function togglePassword(inputId, iconId) {
  const input = document.getElementById(inputId);
  const icon  = document.getElementById(iconId);

  if (!input || !icon) return;

  input.type = input.type === 'password' ? 'text' : 'password';
  icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';

  console.log('[EduFlow Auth] Toggle password visibility:', input.type);
}

function checkStrength(val) {
  const fill  = document.getElementById('pw-fill');
  const label = document.getElementById('pw-label');

  if (!fill || !label) return;

  let score = 0;
  if (val.length >= 8)           score++;
  if (/[A-Z]/.test(val))         score++;
  if (/[0-9]/.test(val))         score++;
  if (/[^A-Za-z0-9]/.test(val))  score++;

  const levels = [
    { pct: '0%',    color: '',          text: '' },
    { pct: '25%',   color: '#ef4444',   text: 'Weak' },       // Thay 'Yếu'
    { pct: '50%',   color: '#f59e0b',   text: 'Average' },    // Thay 'Trung bình'
    { pct: '75%',   color: '#06b6d4',   text: 'Good' },       // Thay 'Tốt'
    { pct: '100%',  color: '#10b981',   text: 'Strong 💪' },  // Thay 'Mạnh'
  ];

  fill.style.width      = levels[score].pct;
  fill.style.background = levels[score].color;
  label.textContent     = levels[score].text;
  label.style.color     = levels[score].color;

  console.log('[EduFlow Auth] Password strength score:', score, '/ 4');
}

window.checkStrength = checkStrength;
window.togglePassword = togglePassword;