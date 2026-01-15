/**
 * Nedir.me Theme JavaScript
 */

(function() {
    'use strict';

    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        initSearch();
        initSmoothScroll();
        initMobileMenu();
    });

    /**
     * Initialize Live Search
     */
    function initSearch() {
        const heroSearch = document.getElementById('hero-search-input');
        const headerSearch = document.getElementById('header-search-input');
        
        [heroSearch, headerSearch].forEach(function(input) {
            if (!input) return;
            
            let debounceTimer;
            
            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value.trim();
                
                if (query.length < 2) {
                    hideSearchResults(input);
                    return;
                }
                
                debounceTimer = setTimeout(function() {
                    performSearch(query, input);
                }, 300);
            });
            
            // Hide results when clicking outside
            document.addEventListener('click', function(e) {
                if (!input.closest('.hero-search, .header-search').contains(e.target)) {
                    hideSearchResults(input);
                }
            });
            
            // Navigate with keyboard
            input.addEventListener('keydown', function(e) {
                const dropdown = input.parentElement.querySelector('.search-dropdown');
                if (!dropdown) return;
                
                const items = dropdown.querySelectorAll('.search-result-item');
                const activeItem = dropdown.querySelector('.search-result-item.active');
                let activeIndex = Array.from(items).indexOf(activeItem);
                
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    activeIndex = Math.min(activeIndex + 1, items.length - 1);
                    setActiveItem(items, activeIndex);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    activeIndex = Math.max(activeIndex - 1, 0);
                    setActiveItem(items, activeIndex);
                } else if (e.key === 'Enter' && activeItem) {
                    e.preventDefault();
                    window.location.href = activeItem.href;
                } else if (e.key === 'Escape') {
                    hideSearchResults(input);
                }
            });
        });
    }

    /**
     * Perform AJAX Search
     */
    function performSearch(query, input) {
        if (typeof nedirAjax === 'undefined') {
            // Fallback: redirect to search page
            return;
        }
        
        const formData = new FormData();
        formData.append('action', 'nedir_search');
        formData.append('nonce', nedirAjax.nonce);
        formData.append('search', query);
        
        fetch(nedirAjax.ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                showSearchResults(data.data, input);
            } else {
                hideSearchResults(input);
            }
        })
        .catch(error => {
            console.error('Search error:', error);
        });
    }

    /**
     * Show Search Results Dropdown
     */
    function showSearchResults(results, input) {
        let dropdown = input.parentElement.querySelector('.search-dropdown');
        
        if (!dropdown) {
            dropdown = document.createElement('div');
            dropdown.className = 'search-dropdown';
            dropdown.style.cssText = `
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--bg-primary);
                border: 1px solid var(--border);
                border-radius: var(--radius-md);
                box-shadow: var(--shadow-lg);
                margin-top: 8px;
                max-height: 400px;
                overflow-y: auto;
                z-index: 1000;
            `;
            input.parentElement.style.position = 'relative';
            input.parentElement.appendChild(dropdown);
        }
        
        const typeIcons = {
            'kavram': '📚',
            'kisi': '👤',
            'video': '🎬',
            'post': '📝'
        };
        
        let html = '';
        results.forEach(function(item) {
            const icon = typeIcons[item.type] || '📄';
            html += `
                <a href="${item.url}" class="search-result-item" style="
                    display: block;
                    padding: 12px 16px;
                    border-bottom: 1px solid var(--border);
                    color: var(--text-primary);
                    text-decoration: none;
                    transition: background 0.15s;
                ">
                    <span style="font-size: 0.75rem; color: var(--text-muted);">${icon}</span>
                    <strong style="margin-left: 8px;">${item.title}</strong>
                    <p style="margin: 4px 0 0; font-size: 0.85rem; color: var(--text-secondary);">${item.excerpt}</p>
                </a>
            `;
        });
        
        dropdown.innerHTML = html;
        dropdown.style.display = 'block';
        
        // Hover effects
        dropdown.querySelectorAll('.search-result-item').forEach(function(item) {
            item.addEventListener('mouseenter', function() {
                this.style.background = 'var(--bg-secondary)';
            });
            item.addEventListener('mouseleave', function() {
                this.style.background = 'transparent';
            });
        });
    }

    /**
     * Hide Search Results Dropdown
     */
    function hideSearchResults(input) {
        const dropdown = input.parentElement.querySelector('.search-dropdown');
        if (dropdown) {
            dropdown.style.display = 'none';
        }
    }

    /**
     * Set Active Item in Dropdown
     */
    function setActiveItem(items, index) {
        items.forEach(function(item, i) {
            if (i === index) {
                item.classList.add('active');
                item.style.background = 'var(--bg-secondary)';
            } else {
                item.classList.remove('active');
                item.style.background = 'transparent';
            }
        });
    }

    /**
     * Smooth Scroll for Anchor Links
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    /**
     * Mobile Menu Toggle
     */
    function initMobileMenu() {
        // Add mobile menu functionality if needed
        const header = document.querySelector('.site-header');
        if (!header) return;
        
        // Add scroll effect
        let lastScroll = 0;
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 100) {
                header.style.boxShadow = 'var(--shadow-md)';
            } else {
                header.style.boxShadow = 'none';
            }
            
            lastScroll = currentScroll;
        });
    }

})();
