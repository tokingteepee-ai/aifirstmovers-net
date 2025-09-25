/**
 * AI First Movers Core Plugin - Frontend JavaScript
 *
 * @package AIFM_Core
 */

(function($) {
    'use strict';

    /**
     * Initialize plugin functionality
     */
    $(document).ready(function() {
        // Initialize resource analytics tracking
        initResourceAnalytics();
        
        // Initialize resource filtering
        initResourceFiltering();
        
        // Initialize carousel functionality
        initCarousel();
        
        // Initialize lazy loading
        initLazyLoading();
    });

    /**
     * Track resource views and downloads
     */
    function initResourceAnalytics() {
        // Track resource views
        if ($('body.single-resource').length > 0) {
            var resourceId = $('body').data('resource-id') || $('article[id^="post-"]').attr('id').replace('post-', '');
            
            if (resourceId) {
                $.post(aifmCore.ajax_url, {
                    action: 'aifm_track_resource_view',
                    resource_id: resourceId,
                    nonce: aifmCore.nonce
                });
            }
        }

        // Track resource downloads
        $('.aifm-download-link, .resource-download-link').on('click', function() {
            var resourceId = $(this).data('resource-id');
            
            if (resourceId) {
                $.post(aifmCore.ajax_url, {
                    action: 'aifm_track_resource_download',
                    resource_id: resourceId,
                    nonce: aifmCore.nonce
                });
            }
        });
    }

    /**
     * Initialize resource filtering functionality
     */
    function initResourceFiltering() {
        $('.aifm-resource-filter').on('change', function() {
            var $container = $(this).closest('.aifm-resource-container');
            var $resources = $container.find('.aifm-resource-item');
            var filterValue = $(this).val();
            var filterType = $(this).data('filter-type');

            if (filterValue === '') {
                $resources.show();
                return;
            }

            $resources.each(function() {
                var $resource = $(this);
                var shouldShow = false;

                if (filterType === 'type') {
                    shouldShow = $resource.find('.aifm-resource-type').text().toLowerCase().includes(filterValue.toLowerCase());
                } else if (filterType === 'difficulty') {
                    shouldShow = $resource.hasClass('aifm-difficulty-' + filterValue);
                } else if (filterType === 'topic') {
                    shouldShow = $resource.data('topics') && $resource.data('topics').includes(filterValue);
                }

                if (shouldShow) {
                    $resource.show();
                } else {
                    $resource.hide();
                }
            });

            // Update count if counter exists
            var visibleCount = $resources.filter(':visible').length;
            $container.find('.aifm-results-count').text(visibleCount + ' resources found');
        });

        // Search functionality
        $('.aifm-resource-search').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();
            var $container = $(this).closest('.aifm-resource-container');
            var $resources = $container.find('.aifm-resource-item');

            if (searchTerm === '') {
                $resources.show();
                return;
            }

            $resources.each(function() {
                var $resource = $(this);
                var title = $resource.find('.aifm-resource-title').text().toLowerCase();
                var excerpt = $resource.find('.aifm-resource-excerpt').text().toLowerCase();

                if (title.includes(searchTerm) || excerpt.includes(searchTerm)) {
                    $resource.show();
                } else {
                    $resource.hide();
                }
            });

            // Update count
            var visibleCount = $resources.filter(':visible').length;
            $container.find('.aifm-results-count').text(visibleCount + ' resources found');
        });
    }

    /**
     * Initialize carousel functionality
     */
    function initCarousel() {
        $('.aifm-style-carousel').each(function() {
            var $carousel = $(this);
            var $items = $carousel.find('.aifm-featured-resource');
            
            if ($items.length <= 1) return;

            // Add navigation buttons
            $carousel.wrap('<div class="aifm-carousel-wrapper"></div>');
            var $wrapper = $carousel.parent();
            
            $wrapper.append('<button class="aifm-carousel-prev" aria-label="Previous"><span>&lt;</span></button>');
            $wrapper.append('<button class="aifm-carousel-next" aria-label="Next"><span>&gt;</span></button>');

            var currentIndex = 0;
            var itemWidth = $items.first().outerWidth(true);

            function updateCarousel() {
                var translateX = -currentIndex * itemWidth;
                $carousel.css('transform', 'translateX(' + translateX + 'px)');
                
                // Update button states
                $wrapper.find('.aifm-carousel-prev').toggleClass('disabled', currentIndex === 0);
                $wrapper.find('.aifm-carousel-next').toggleClass('disabled', currentIndex >= $items.length - 1);
            }

            $wrapper.find('.aifm-carousel-prev').on('click', function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            });

            $wrapper.find('.aifm-carousel-next').on('click', function() {
                if (currentIndex < $items.length - 1) {
                    currentIndex++;
                    updateCarousel();
                }
            });

            // Initialize
            updateCarousel();

            // Handle resize
            $(window).on('resize', function() {
                itemWidth = $items.first().outerWidth(true);
                updateCarousel();
            });
        });
    }

    /**
     * Initialize lazy loading for images
     */
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            var imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            $('.aifm-resource-card img[data-src], .aifm-featured-resource img[data-src]').each(function() {
                imageObserver.observe(this);
            });
        }
    }

    /**
     * Utility function for smooth scrolling
     */
    function smoothScrollTo(target, duration) {
        duration = duration || 500;
        var targetPosition = $(target).offset().top - 80;
        
        $('html, body').animate({
            scrollTop: targetPosition
        }, duration);
    }

    /**
     * Initialize accessibility enhancements
     */
    function initAccessibility() {
        // Add ARIA labels to interactive elements
        $('.aifm-resource-card').each(function() {
            var title = $(this).find('.aifm-resource-title a').text();
            $(this).attr('aria-label', 'Resource: ' + title);
        });

        // Keyboard navigation for carousels
        $('.aifm-carousel-wrapper').on('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                $(this).find('.aifm-carousel-prev').click();
                e.preventDefault();
            } else if (e.key === 'ArrowRight') {
                $(this).find('.aifm-carousel-next').click();
                e.preventDefault();
            }
        });

        // Focus management
        $('.aifm-resource-card a, .aifm-featured-resource a').on('focus', function() {
            $(this).closest('.aifm-resource-card, .aifm-featured-resource').addClass('focused');
        }).on('blur', function() {
            $(this).closest('.aifm-resource-card, .aifm-featured-resource').removeClass('focused');
        });
    }

    // Initialize accessibility when DOM is ready
    $(document).ready(initAccessibility);

})(jQuery);