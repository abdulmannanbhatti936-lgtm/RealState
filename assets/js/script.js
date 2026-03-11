// Basic interactivity for RealEstate Pro

document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId && targetId !== '#') {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // Simple micro-interactions
    const logo = document.querySelector('.logo');
    if (logo) {
        logo.addEventListener('mouseover', () => {
            logo.style.transition = 'color 0.3s ease';
            logo.style.color = 'var(--accent-color)';
        });
        logo.addEventListener('mouseout', () => {
            logo.style.color = 'var(--white)';
        });
    }

    // Mobile Nav Toggles
    const mobileToggle = document.getElementById('mobile-nav-toggle');
    const adminToggle = document.getElementById('admin-mobile-toggle');
    const navMenu = document.getElementById('nav-menu');
    const adminMenu = document.getElementById('admin-nav-menu');

    if (mobileToggle) {
        mobileToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            mobileToggle.querySelector('i').classList.toggle('fa-bars');
            mobileToggle.querySelector('i').classList.toggle('fa-xmark');
        });
    }

    if (adminToggle) {
        adminToggle.addEventListener('click', () => {
            adminMenu.classList.toggle('active');
            adminToggle.querySelector('i').classList.toggle('fa-bars-staggered');
            adminToggle.querySelector('i').classList.toggle('fa-xmark');
        });
    }

    console.log('RealEstate Pro scripts loaded.');
});