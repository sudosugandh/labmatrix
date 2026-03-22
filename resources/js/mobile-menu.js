/**
 * Mobile menu handler for responsive sidebar
 */
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    
    if (mobileMenuButton && sidebar) {
        // Toggle sidebar
        mobileMenuButton.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            if (sidebarOverlay) {
                sidebarOverlay.classList.toggle('hidden');
            }
            document.body.classList.toggle('overflow-hidden');
        });
        
        // Close sidebar when clicking overlay
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 1024) {
                if (!sidebar.contains(e.target) && !mobileMenuButton.contains(e.target)) {
                    if (!sidebar.classList.contains('-translate-x-full')) {
                        sidebar.classList.add('-translate-x-full');
                        if (sidebarOverlay) {
                            sidebarOverlay.classList.add('hidden');
                        }
                        document.body.classList.remove('overflow-hidden');
                    }
                }
            }
        });
    }
});
