const initSidebarHandler = () => {
    const sidebar = document.getElementById('sidebar');
    const dashboard = document.querySelector('.app-width');

    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class') {
                const isFolded = sidebar.classList.contains('sidebar-narrow') || 
                               sidebar.classList.contains('sidebar-narrow-unfoldable');
                
                requestAnimationFrame(() => {
                    if (isFolded) {
                        dashboard.classList.remove('px-4');
                        dashboard.style.maxWidth = '90%';
                    } else {
                        dashboard.classList.add('px-4');
                        dashboard.style.maxWidth = '';
                    }
                });
            }
        });
    });

    observer.observe(sidebar, {
        attributes: true
    });
};

export { initSidebarHandler };

