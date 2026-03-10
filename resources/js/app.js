const toggle = document.getElementById('sidebarToggle');
const overlay = document.getElementById('sidebarOverlay');
const sidebar = document.querySelector('.sidebar');

toggle?.addEventListener('click', () => {
    const isOpen = sidebar.classList.toggle('open');
    overlay.classList.toggle('active');
    toggle.style.left = isOpen ? '254px' : '14px';
    console.log('Sidebar toggled:', isOpen);
});

overlay?.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('active');
    toggle.style.left = '14px';
});