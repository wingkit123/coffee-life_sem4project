// global.js - Universal JavaScript functionality for Coffee's Life
document.addEventListener('DOMContentLoaded', function() {
    // Apply coffee theme enhancements
    enhanceCoffeeTheme();
    
    // Menu functionality
    const menu = document.getElementById('sideMenu');
    const menuToggle = document.querySelector('.menu-toggle');

    // Global menu toggle function
    window.toggleMenu = function() {
        menu.classList.toggle('active');
        
        // Add coffee bean animation to menu button
        if (menuToggle) {
            menuToggle.classList.add('menu-active');
            setTimeout(() => {
                menuToggle.classList.remove('menu-active');
            }, 300);
        }
    };

    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!menu.contains(event.target) && !event.target.classList.contains('menu-toggle')) {
            menu.classList.remove('active');
        }
    });

   
});

// Coffee theme enhancement function
function enhanceCoffeeTheme() {
    // Add coffee-themed classes to body based on page
    const currentPage = window.location.pathname.split('/').pop();
    document.body.classList.add('coffee-theme');
    
    if (currentPage) {
        document.body.classList.add('page-' + currentPage.replace('.php', ''));
    }
    
    // Add hover effects to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px) scale(1.02)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add coffee steam animation to logos/images
    const logos = document.querySelectorAll('.logo-home-link img');
    logos.forEach(logo => {
        logo.addEventListener('mouseenter', function() {
            this.style.filter = 'drop-shadow(0 0 20px rgba(111, 78, 55, 0.3))';
        });
        
        logo.addEventListener('mouseleave', function() {
            this.style.filter = 'drop-shadow(3px 3px 6px rgba(0,0,0,0.8))';
        });
    });
}



// Utility function for form validation (can be used across pages)
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Utility function for phone validation
function validatePhone(phone) {
    const re = /^[\+]?[0-9\s\-\(\)]{10,}$/;
    return re.test(phone);
}

// Form validation helper
window.showFormError = function(field, message) {
    const errorElement = document.getElementById(field + '_error');
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.color = '#dc3545';
        errorElement.style.fontSize = '0.9em';
        errorElement.style.marginTop = '5px';
    }
};

window.clearFormErrors = function() {
    const errorElements = document.querySelectorAll('[id$="_error"]');
    errorElements.forEach(element => {
        element.textContent = '';
    });
};

// Enhanced notification system for Coffee's Life
window.showNotification = function(message, type = 'info', duration = 5000) {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.coffee-notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `coffee-notification alert ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${getNotificationIcon(type)}"></i>
            <span>${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    // Style the notification
    Object.assign(notification.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        zIndex: '10000',
        maxWidth: '400px',
        opacity: '0',
        transform: 'translateX(100%)',
        transition: 'all 0.3s ease'
    });
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto remove
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, duration);
};

function getNotificationIcon(type) {
    switch(type) {
        case 'success': return 'check-circle';
        case 'error': return 'exclamation-triangle';
        case 'warning': return 'exclamation-triangle';
        case 'info': 
        default: return 'info-circle';
    }
}

// Coffee-themed loading animation
window.showCoffeeLoader = function() {
    const loader = document.createElement('div');
    loader.className = 'coffee-loader';
    loader.innerHTML = `
        <div class="coffee-cup">
            <i class="fas fa-coffee"></i>
        </div>
        <div class="loading-text">Brewing your request...</div>
    `;
    
    Object.assign(loader.style, {
        position: 'fixed',
        top: '0',
        left: '0',
        width: '100%',
        height: '100%',
        background: 'rgba(111, 78, 55, 0.8)',
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'center',
        alignItems: 'center',
        zIndex: '10001',
        color: 'white'
    });
    
    document.body.appendChild(loader);
    return loader;
};

window.hideCoffeeLoader = function(loader) {
    if (loader) {
        loader.style.opacity = '0';
        setTimeout(() => loader.remove(), 300);
    }
};
