/**
 * HaoHao Mandarin Theme JavaScript
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Mobile Navigation Toggle
        var menuToggle = document.getElementById('menu-toggle');
        var siteNav = document.getElementById('site-nav');

        if (menuToggle && siteNav) {
            menuToggle.addEventListener('click', function () {
                var isExpanded = siteNav.style.display === 'block';
                siteNav.style.display = isExpanded ? 'none' : 'block';
            });
        }

        // Smooth Scrolling for In-Page Anchor Links
        document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
            anchor.addEventListener('click', function (e) {
                var targetId = this.getAttribute('href');
                if (targetId && targetId !== '#') {
                    var targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        e.preventDefault();
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
    });
})();
