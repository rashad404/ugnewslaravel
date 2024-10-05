document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggleDesktop = document.getElementById('sidebar-toggle-desktop');
    const sidebarToggleMobile = document.getElementById('sidebar-toggle-mobile');
    const sidebar = document.getElementById('sidebar');
    const content = document.querySelector('.flex-1.flex.flex-col');
    const topNavigation = document.getElementById('topNavigation');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        content.classList.add('md:ml-64');
        content.classList.add('ml-0');
        topNavigation.classList.remove('ml-4');
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        sidebar.classList.remove('md:translate-x-0');
        sidebar.classList.remove('-translate-x-0');
        content.classList.remove('md:ml-64');
        topNavigation.classList.add('ml-4');
    }

    sidebarToggleDesktop.addEventListener('click', openSidebar);
    sidebarToggleMobile.addEventListener('click', closeSidebar);

    // Close sidebar when clicking outside of it on mobile
    document.addEventListener('click', function(event) {
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnToggleButton = sidebarToggleDesktop.contains(event.target);
        const isMobile = window.innerWidth < 768; // Adjust this value based on your md breakpoint

        if (isMobile && !isClickInsideSidebar && !isClickOnToggleButton && !sidebar.classList.contains('-translate-x-full')) {
            toggleSidebar();
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            sidebar.classList.remove('-translate-x-full');
            content.classList.add('md:ml-64');
            content.classList.remove('ml-0');
        } else {
            sidebar.classList.add('-translate-x-full');
            content.classList.remove('md:ml-64');
            content.classList.add('ml-0');
        }
    });
});