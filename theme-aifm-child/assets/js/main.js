/**
 * AI First Movers Child Theme JavaScript
 *
 * @package AIFM_Child
 * @version 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Initialize theme functionality when DOM is ready
     */
    $(document).ready(function() {
        // Initialize smooth scrolling for anchor links
        initSmoothScrolling();
        
        // Initialize mobile menu toggle
        initMobileMenu();
        
        // Initialize scroll-to-top functionality
        initScrollToTop();
        
        // Initialize form enhancements
        initFormEnhancements();
    });

    /**
     * Smooth scrolling for anchor links
     */
    function initSmoothScrolling() {
        $('a[href*="#"]:not([href="#"])').click(function() {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') 
                && location.hostname === this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 1000);
                    return false;
                }
            }
        });
    }

    /**
     * Mobile menu toggle functionality
     */
    function initMobileMenu() {
        // Add mobile menu button if it doesn't exist
        if (!$('.mobile-menu-toggle').length) {
            $('.main-navigation').prepend('<button class="mobile-menu-toggle" aria-label="Toggle mobile menu"><span></span><span></span><span></span></button>');
        }

        $('.mobile-menu-toggle').on('click', function() {
            $(this).toggleClass('active');
            $('.main-navigation ul').toggleClass('show');
        });

        // Close mobile menu on window resize
        $(window).on('resize', function() {
            if ($(window).width() > 768) {
                $('.mobile-menu-toggle').removeClass('active');
                $('.main-navigation ul').removeClass('show');
            }
        });
    }

    /**
     * Scroll to top functionality
     */
    function initScrollToTop() {
        // Add scroll to top button
        $('body').append('<button id="scroll-to-top" aria-label="Scroll to top"><i class="dashicons dashicons-arrow-up-alt2"></i></button>');

        // Show/hide scroll to top button
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $('#scroll-to-top').addClass('show');
            } else {
                $('#scroll-to-top').removeClass('show');
            }
        });

        // Scroll to top action
        $('#scroll-to-top').on('click', function() {
            $('html, body').animate({scrollTop: 0}, 600);
        });
    }

    /**
     * Form enhancements
     */
    function initFormEnhancements() {
        // Add focus classes to form fields
        $('input, textarea, select').on('focus', function() {
            $(this).parent().addClass('field-focused');
        }).on('blur', function() {
            $(this).parent().removeClass('field-focused');
        });

        // Form validation enhancement
        $('form').on('submit', function() {
            var isValid = true;
            $(this).find('input[required], textarea[required], select[required]').each(function() {
                if (!$(this).val()) {
                    $(this).addClass('error');
                    isValid = false;
                } else {
                    $(this).removeClass('error');
                }
            });
            return isValid;
        });
    }

    /**
     * Initialize accessibility enhancements
     */
    function initAccessibility() {
        // Skip link functionality
        $('.skip-link').on('click', function(e) {
            var target = $($(this).attr('href'));
            if (target.length) {
                target.attr('tabindex', '-1').focus();
            }
        });

        // Keyboard navigation for dropdowns
        $('.menu-item-has-children > a').on('keydown', function(e) {
            if (e.keyCode === 13 || e.keyCode === 32) { // Enter or Space
                e.preventDefault();
                $(this).next('.sub-menu').toggle();
            }
        });
    }

    // Initialize accessibility when DOM is ready
    $(document).ready(initAccessibility);

})(jQuery);