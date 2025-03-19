function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const toggleBtn = document.querySelector('.toggle_btn');
    sidebar.classList.toggle('open');
    content.classList.toggle('shrink');
   
    if (sidebar.classList.contains('open')) {
        toggleBtn.style.display = 'none';
    } else {
        toggleBtn.style.display = 'block';
    }
}