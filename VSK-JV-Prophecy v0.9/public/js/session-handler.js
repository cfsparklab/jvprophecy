/**
 * Global Session Expiry Handler
 * Automatically handles 419 Page Expired errors and redirects to home
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        homeUrl: '/',
        redirectDelay: 3000, // 3 seconds
        showNotification: true,
        debug: false
    };

    // Utility functions
    function log(message, type = 'info') {
        if (CONFIG.debug) {
            console[type]('[SessionHandler]', message);
        }
    }

    function showNotification(message, type = 'warning') {
        if (!CONFIG.showNotification) return;

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `session-notification session-${type}`;
        notification.innerHTML = `
            <div class="session-notification-content">
                <span class="session-notification-icon">⚠️</span>
                <span class="session-notification-message">${message}</span>
                <button class="session-notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
        `;

        // Add styles if not already added
        if (!document.querySelector('#session-notification-styles')) {
            const styles = document.createElement('style');
            styles.id = 'session-notification-styles';
            styles.textContent = `
                .session-notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
                    color: #0f172a;
                    padding: 1rem 1.5rem;
                    border-radius: 12px;
                    box-shadow: 0 8px 25px rgba(251, 191, 36, 0.3);
                    z-index: 10000;
                    max-width: 400px;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    font-size: 14px;
                    animation: slideIn 0.3s ease-out;
                }
                
                .session-notification-content {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }
                
                .session-notification-icon {
                    font-size: 1.2rem;
                }
                
                .session-notification-message {
                    flex: 1;
                    font-weight: 500;
                }
                
                .session-notification-close {
                    background: none;
                    border: none;
                    font-size: 1.2rem;
                    cursor: pointer;
                    color: #0f172a;
                    padding: 0;
                    width: 20px;
                    height: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 50%;
                    transition: background-color 0.2s;
                }
                
                .session-notification-close:hover {
                    background-color: rgba(15, 23, 42, 0.1);
                }
                
                @keyframes slideIn {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                
                .session-error {
                    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                    color: white;
                }
            `;
            document.head.appendChild(styles);
        }

        // Add to page
        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    function redirectToHome(delay = CONFIG.redirectDelay) {
        log(`Redirecting to home in ${delay}ms`);
        
        setTimeout(() => {
            log('Redirecting to home now');
            window.location.href = CONFIG.homeUrl;
        }, delay);
    }

    function handle419Error(xhr = null) {
        log('Handling 419 Page Expired error');
        
        // Show notification
        showNotification('Your session has expired. Redirecting to home page...', 'warning');
        
        // Redirect to home
        redirectToHome();
        
        // If it's an AJAX response with redirect URL, use that
        if (xhr && xhr.responseJSON && xhr.responseJSON.redirect) {
            setTimeout(() => {
                window.location.href = xhr.responseJSON.redirect;
            }, CONFIG.redirectDelay);
        }
    }

    // Setup global AJAX error handler for jQuery (if available)
    function setupJQueryHandler() {
        if (typeof $ !== 'undefined' && $.ajaxSetup) {
            log('Setting up jQuery AJAX error handler');
            
            $(document).ajaxError(function(event, xhr, settings) {
                if (xhr.status === 419) {
                    log('jQuery AJAX 419 error detected');
                    handle419Error(xhr);
                }
            });
        }
    }

    // Setup global fetch error handler
    function setupFetchHandler() {
        log('Setting up Fetch API error handler');
        
        // Override fetch to handle 419 errors
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            return originalFetch.apply(this, args)
                .then(response => {
                    if (response.status === 419) {
                        log('Fetch API 419 error detected');
                        handle419Error();
                    }
                    return response;
                })
                .catch(error => {
                    // Re-throw the error for normal error handling
                    throw error;
                });
        };
    }

    // Setup global XMLHttpRequest error handler
    function setupXHRHandler() {
        log('Setting up XMLHttpRequest error handler');
        
        const originalOpen = XMLHttpRequest.prototype.open;
        XMLHttpRequest.prototype.open = function(...args) {
            this.addEventListener('readystatechange', function() {
                if (this.readyState === 4 && this.status === 419) {
                    log('XMLHttpRequest 419 error detected');
                    handle419Error(this);
                }
            });
            return originalOpen.apply(this, args);
        };
    }

    // Setup form submission handler
    function setupFormHandler() {
        log('Setting up form submission handler');
        
        document.addEventListener('submit', function(event) {
            const form = event.target;
            if (form.tagName === 'FORM') {
                // Add a hidden input with current timestamp to help detect stale forms
                const timestampInput = document.createElement('input');
                timestampInput.type = 'hidden';
                timestampInput.name = '_timestamp';
                timestampInput.value = Date.now();
                form.appendChild(timestampInput);
            }
        });
    }

    // Setup page visibility handler to refresh token when page becomes visible
    function setupVisibilityHandler() {
        log('Setting up page visibility handler');
        
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                // Page became visible, check if we need to refresh CSRF token
                const metaToken = document.querySelector('meta[name="csrf-token"]');
                if (metaToken) {
                    // Optionally ping server to check session status
                    fetch('/api/session-check', {
                        method: 'GET',
                        credentials: 'same-origin'
                    }).catch(() => {
                        // If session check fails, assume session expired
                        log('Session check failed, assuming expired');
                    });
                }
            }
        });
    }

    // Initialize session handler
    function init() {
        log('Initializing Session Handler');
        
        // Set home URL from meta tag if available
        const metaHome = document.querySelector('meta[name="home-url"]');
        if (metaHome) {
            CONFIG.homeUrl = metaHome.getAttribute('content');
        }
        
        // Setup all handlers
        setupJQueryHandler();
        setupFetchHandler();
        setupXHRHandler();
        setupFormHandler();
        setupVisibilityHandler();
        
        log('Session Handler initialized successfully');
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Expose global function for manual handling
    window.handleSessionExpiry = handle419Error;
    
    log('Session Handler script loaded');
})();
