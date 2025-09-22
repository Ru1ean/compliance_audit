    /**
 * Navbar synchronization script
 * This script ensures that the active state in the navbar iframe
 * corresponds to the current page loaded in the content iframe
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get the current page filename
    const currentPage = window.location.pathname.split('/').pop();
    
    // Function to set active navbar item in parent iframe
    function setActiveNavItem() {
        try {
            // Get the navbar iframe from the parent window
            const navbarIframe = window.parent.document.querySelector('.column1');
            if (navbarIframe && navbarIframe.contentWindow) {
                // Call the setActiveNavItem function in the navbar window if available
                if (typeof navbarIframe.contentWindow.setActiveNavItem === 'function') {
                    navbarIframe.contentWindow.setActiveNavItem(currentPage);
                } else {
                    // Fallback to direct DOM manipulation if function is not available
                    const navLinks = navbarIframe.contentWindow.document.querySelectorAll('.navbar-link');
                    
                    // Remove active class from all links
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        
                        // Add active class to the link that matches current page
                        const href = link.getAttribute('href');
                        if (href === currentPage || 
                            (href && currentPage.startsWith(href.split('?')[0]))) {
                            link.classList.add('active');
                        }
                    });
                }
            }
        } catch (error) {
            console.error('Error setting active navbar item:', error);
        }
    }
    
    // Call the function when the page loads
    setActiveNavItem();
    
    // Also call it when the page visibility changes (e.g., when returning to the tab)
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            setActiveNavItem();
        }
    });
}); 