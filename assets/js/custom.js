/**
 * Custom JavaScript for Chrysoberyl Theme
 * 
 * @package chrysoberyl
 * @since 1.0.0
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Mobile Menu Toggle
        window.toggleMobileMenu = function() {
            const drawer = document.getElementById('mobile-menu-drawer');
            const button = document.getElementById('mobile-menu-button');
            if (!drawer || !button) return;

            const isHidden = drawer.classList.contains('hidden');
            if (isHidden) {
                drawer.classList.remove('hidden');
                drawer.setAttribute('aria-hidden', 'false');
                button.setAttribute('aria-expanded', 'true');
                var openIcon = document.querySelector('#main-header .mobile-menu-icon-open');
                var closeIcon = document.querySelector('#main-header .mobile-menu-icon-close');
                if (openIcon) openIcon.classList.add('hidden');
                if (closeIcon) closeIcon.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                drawer.classList.add('hidden');
                drawer.setAttribute('aria-hidden', 'true');
                button.setAttribute('aria-expanded', 'false');
                var openIcon = document.querySelector('#main-header .mobile-menu-icon-open');
                var closeIcon = document.querySelector('#main-header .mobile-menu-icon-close');
                if (openIcon) openIcon.classList.remove('hidden');
                if (closeIcon) closeIcon.classList.add('hidden');
                document.body.style.overflow = '';
            }
        };

        window.toggleLanguageModal = function() {
            var modal = document.getElementById('chrysoberyl-language-modal');
            if (!modal) return;
            var isHidden = modal.classList.contains('hidden');
            modal.classList.toggle('hidden');
            modal.setAttribute('aria-hidden', isHidden ? 'false' : 'true');
            document.body.style.overflow = isHidden ? 'hidden' : '';
        };

        // Category filter dropdown (mockup Phase 3: mobile)
        window.toggleCategoryDropdown = function() {
            var dropdown = document.getElementById('category-dropdown');
            var button = document.getElementById('category-filter-toggle');
            var chevron = document.getElementById('category-chevron');
            if (!dropdown || !button) return;
            var isOpen = !dropdown.classList.contains('hidden');
            dropdown.classList.toggle('hidden', isOpen);
            button.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
            if (chevron) chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
        };
        $(document).on('click', '.chrysoberyl-category-toggle', function() {
            toggleCategoryDropdown();
        });
        $(document).on('click', '.chrysoberyl-category-link', function() {
            var label = $(this).data('label');
            if (label && document.getElementById('selected-category')) {
                document.getElementById('selected-category').textContent = label;
            }
            var dd = document.getElementById('category-dropdown');
            if (dd) { dd.classList.add('hidden'); }
            var btn = document.getElementById('category-filter-toggle');
            if (btn) { btn.setAttribute('aria-expanded', 'false'); }
            var chevron = document.getElementById('category-chevron');
            if (chevron) { chevron.style.transform = ''; }
        });
        $(document).on('click', function(e) {
            if ($(e.target).closest('.chrysoberyl-category-toggle, .chrysoberyl-category-dropdown').length) return;
            var dd = document.getElementById('category-dropdown');
            if (dd && !dd.classList.contains('hidden')) {
                dd.classList.add('hidden');
                var btn = document.getElementById('category-filter-toggle');
                if (btn) btn.setAttribute('aria-expanded', 'false');
                var chevron = document.getElementById('category-chevron');
                if (chevron) chevron.style.transform = '';
            }
        });

        // Categories widget: collapsible + remember open state (localStorage)
        (function() {
            var STORAGE_KEY = 'chrysoberyl_cat_open';
            function getOpenIds() {
                try {
                    var raw = localStorage.getItem(STORAGE_KEY);
                    return raw ? JSON.parse(raw) : [];
                } catch (e) {
                    return [];
                }
            }
            function setOpenIds(ids) {
                try {
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));
                } catch (e) {}
            }
            function applySavedState() {
                var ids = getOpenIds();
                ids.forEach(function(id) {
                    var wrap = document.querySelector('.chrysoberyl-categories-collapsible .chrysoberyl-cat-item-wrap[data-term-id="' + id + '"]');
                    if (wrap && wrap.querySelector('.chrysoberyl-categories-children')) {
                        wrap.classList.add('open');
                        var btn = wrap.querySelector('.chrysoberyl-cat-toggle');
                        if (btn) btn.setAttribute('aria-expanded', 'true');
                    }
                });
            }
            applySavedState();
            $(document).on('click', '.chrysoberyl-cat-toggle', function(e) {
                e.preventDefault();
                var btn = this;
                var tid = parseInt($(btn).data('term-id'), 10);
                var wrap = $(btn).closest('.chrysoberyl-cat-item-wrap')[0];
                if (!wrap) return;
                var isOpen = wrap.classList.toggle('open');
                btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                var ids = getOpenIds();
                if (isOpen) {
                    if (ids.indexOf(tid) === -1) ids.push(tid);
                } else {
                    ids = ids.filter(function(i) { return i !== tid; });
                }
                setOpenIds(ids);
            });
        })();

        // Rank Math Sitemap page: collapsible sections (h2/h3 + next ul)
        (function() {
            var container = document.querySelector('.chrysoberyl-sitemap-page .chrysoberyl-sitemap-lists');
            if (!container) return;
            var headers = container.querySelectorAll('h2, h3');
            headers.forEach(function(h) {
                var next = h.nextElementSibling;
                if (!next) return;
                var toggle = document.createElement('span');
                toggle.className = 'sitemap-section-toggle';
                toggle.setAttribute('aria-hidden', 'true');
                toggle.innerHTML = '<i class="fas fa-chevron-down"></i>';
                h.appendChild(toggle);
                h.setAttribute('role', 'button');
                h.setAttribute('tabindex', '0');
                h.setAttribute('aria-expanded', 'true');
                h.addEventListener('click', function() {
                    h.classList.toggle('is-collapsed');
                    h.setAttribute('aria-expanded', h.classList.contains('is-collapsed') ? 'false' : 'true');
                });
                h.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        h.click();
                    }
                });
            });
        })();

        // Image lightbox: ภาพประกอบบทความ – คลิกขยายแบบ modal (ไม่ไปหน้าใหม่)
        (function() {
            var modal = document.getElementById('chrysoberyl-image-lightbox');
            var modalImg = modal ? modal.querySelector('.chrysoberyl-image-lightbox-img') : null;
            var backdrop = modal ? modal.querySelector('.chrysoberyl-image-lightbox-backdrop') : null;
            var closeBtn = modal ? modal.querySelector('.chrysoberyl-image-lightbox-close') : null;

            function isImageUrl(url) {
                if (!url) return false;
                return /\.(jpe?g|png|gif|webp|avif)(\?|$)/i.test(url) || /\/wp-content\/uploads\//i.test(url);
            }

            function openLightbox(src, alt) {
                if (!modal || !modalImg) return;
                modalImg.src = src;
                modalImg.alt = alt || '';
                modal.classList.remove('hidden');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                $('body').addClass('chrysoberyl-image-lightbox-open');
                if (closeBtn) closeBtn.focus();
            }

            function closeLightbox() {
                if (!modal) return;
                modal.classList.add('hidden');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                $('body').removeClass('chrysoberyl-image-lightbox-open');
            }

            $(document).on('click', '.chrysoberyl-article-content img, .chrysoberyl-article-content figure img, .chrysoberyl-article-content .wp-block-image img', function(e) {
                var img = this;
                var link = img.closest('a');
                var src = (link && isImageUrl(link.href)) ? link.href : (img.dataset.fullUrl || img.currentSrc || img.src);
                if (!src) return;
                e.preventDefault();
                e.stopPropagation();
                openLightbox(src, img.alt || '');
            });

            $(document).on('click', '.chrysoberyl-article-content a:has(img)', function(e) {
                var link = this;
                var img = link.querySelector('img');
                if (!img || !isImageUrl(link.href)) return;
                e.preventDefault();
                e.stopPropagation();
                openLightbox(link.href, img.alt || '');
            });

            if (backdrop) $(backdrop).on('click', closeLightbox);
            if (closeBtn) $(closeBtn).on('click', closeLightbox);

            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) closeLightbox();
            });
        })();

        // Category Filtering
        $('.category-filter').on('click', function() {
            const category = $(this).data('category');
            
            // Remove active class from all filters
            $('.category-filter').removeClass('active bg-accent text-white').addClass('bg-gray-100 text-gray-700');
            
            // Add active class to clicked filter
            $(this).addClass('active bg-accent text-white').removeClass('bg-gray-100 text-gray-700');
            
            // Filter posts via AJAX
            if (typeof chrysoberylAjax !== 'undefined') {
                $.ajax({
                    url: chrysoberylAjax.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'filter_posts',
                        category: category,
                        nonce: chrysoberylAjax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#news-grid').html(response.data.html);
                        }
                    }
                });
            }
        });

        // Load More Posts
        $('#load-more-btn').on('click', function() {
            const button = $(this);
            const currentPage = parseInt(button.data('page')) || 1;
            const nextPage = currentPage + 1;
            
            button.prop('disabled', true);
            const loadingText = (typeof chrysoberylAjax !== 'undefined' && chrysoberylAjax.load_more_loading) ? chrysoberylAjax.load_more_loading : 'Loading...';
            button.html('<i class="fas fa-spinner fa-spin mr-2"></i>' + loadingText);
            
            if (typeof chrysoberylAjax !== 'undefined') {
                // Get current query parameters
                const ajaxData = {
                    action: 'load_more_posts',
                    page: nextPage,
                    nonce: chrysoberylAjax.nonce
                };
                
                // Check if we're on archive page (category or tag)
                const urlParams = new URLSearchParams(window.location.search);
                const catId = button.data('cat-id') || urlParams.get('cat') || '';
                const tagId = button.data('tag-id') || urlParams.get('tag_id') || '';
                const searchQuery = button.data('search') || urlParams.get('s') || '';
                
                if (catId) {
                    ajaxData.cat_id = catId;
                }
                if (tagId) {
                    ajaxData.tag_id = tagId;
                }
                if (searchQuery) {
                    ajaxData.search = searchQuery;
                }
                
                // Get category from active filter (if exists)
                const activeFilter = $('.category-filter.active');
                if (activeFilter.length && activeFilter.data('category') && activeFilter.data('category') !== 'all') {
                    ajaxData.category = activeFilter.data('category');
                }
                
                $.ajax({
                    url: chrysoberylAjax.ajaxurl,
                    type: 'POST',
                    data: ajaxData,
                    success: function(response) {
                        if (response.success) {
                            // Find the grid container (works for both home and archive)
                            let gridContainer = $('#news-grid');
                            if (!gridContainer.length) {
                                // Try to find grid container in archive/search pages
                                gridContainer = $('.grid.grid-cols-1.md\\:grid-cols-2').first();
                            }
                            if (!gridContainer.length) {
                                // For search page, use space-y-6 container
                                gridContainer = $('.space-y-6').first();
                            }
                            
                            if (gridContainer.length) {
                                gridContainer.append(response.data.html);
                            } else {
                                // Fallback: append before the button
                                button.before(response.data.html);
                            }
                            
                            button.data('page', nextPage);
                            
                            if (!response.data.has_more) {
                                button.hide();
                            } else {
                                button.prop('disabled', false);
                                const labelText = (typeof chrysoberylAjax !== 'undefined' && chrysoberylAjax.load_more_label) ? chrysoberylAjax.load_more_label : 'Load more';
                                button.html('<span class="relative z-10">' + labelText + '</span><i class="fas fa-arrow-down ml-2 relative z-10"></i>');
                            }
                        }
                    },
                    error: function() {
                        button.prop('disabled', false);
                        const labelText = (typeof chrysoberylAjax !== 'undefined' && chrysoberylAjax.load_more_label) ? chrysoberylAjax.load_more_label : 'Load more';
                        button.html('<span class="relative z-10">' + labelText + '</span><i class="fas fa-arrow-down ml-2 relative z-10"></i>');
                    }
                });
            }
        });

        // Social Share - Copy Link
        $(document).on('click', '.chrysoberyl-share-copy_link', function(e) {
            e.preventDefault();
            const button = $(this);
            const postUrl = button.closest('.chrysoberyl-social-share').data('post-url') || button.data('post-url');
            const url = postUrl || window.location.href;
            
            // Copy to clipboard
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(function() {
                    showCopyToast();
                }).catch(function() {
                    fallbackCopyTextToClipboard(url);
                });
            } else {
                fallbackCopyTextToClipboard(url);
            }
        });
        
        // Fallback copy function
        function fallbackCopyTextToClipboard(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showCopyToast();
                }
            } catch (err) {
                console.error('Fallback: Could not copy text', err);
            }
            
            document.body.removeChild(textArea);
        }
        
        // Show copy toast notification
        function showCopyToast() {
            // Remove existing toast
            $('.chrysoberyl-copy-toast').remove();
            
            // Create toast
            const toast = $('<div class="chrysoberyl-copy-toast"><i class="fas fa-check-circle"></i><span>คัดลอกลิงก์เรียบร้อยแล้ว</span></div>');
            $('body').append(toast);
            
            // Show toast
            setTimeout(function() {
                toast.addClass('show');
            }, 100);
            
            // Hide toast after 3 seconds
            setTimeout(function() {
                toast.removeClass('show');
                setTimeout(function() {
                    toast.remove();
                }, 300);
            }, 3000);
        }
        
        // Floating Share Buttons Toggle
        $(document).on('click', '.chrysoberyl-floating-share-toggle', function(e) {
            e.preventDefault();
            const floatingShare = $(this).closest('.chrysoberyl-floating-share');
            floatingShare.toggleClass('active');
        });
        
        // Close floating share when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.chrysoberyl-floating-share').length) {
                $('.chrysoberyl-floating-share').removeClass('active');
            }
        });
        
        // Handle copy link for floating buttons
        $(document).on('click', '.chrysoberyl-floating-share-btn.chrysoberyl-share-copy_link', function(e) {
            e.preventDefault();
            const button = $(this);
            const postUrl = button.data('post-url') || window.location.href;
            const url = postUrl || window.location.href;
            
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(function() {
                    showCopyToast();
                }).catch(function() {
                    fallbackCopyTextToClipboard(url);
                });
            } else {
                fallbackCopyTextToClipboard(url);
            }
        });

        // Back to Top Button — แสดงเมื่อ scroll > 300px, มุมล่างขวา, ตรง mockup
        const backToTopBtn = $('#back-to-top');
        if (backToTopBtn.length) {
            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    backToTopBtn.removeClass('opacity-0 translate-y-20 pointer-events-none').addClass('opacity-100 translate-y-0');
                } else {
                    backToTopBtn.addClass('opacity-0 translate-y-20 pointer-events-none').removeClass('opacity-100 translate-y-0');
                }
            });
        }

        // Newsletter Form Handler (jQuery fallback for older forms)
        $('form[onsubmit*="handleNewsletterSubmit"]').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const emailInput = form.find('input[type="email"]');
            const email = emailInput.val().trim();
            const button = form.find('button[type="submit"]');
            const originalText = button.html();
            
            if (!email) {
                emailInput.focus();
                return;
            }
            
            button.html('<i class="fas fa-spinner fa-spin"></i>');
            button.prop('disabled', true);
            
            // Simulate API call (replace with actual API endpoint)
            setTimeout(function() {
                // Success state
                button.html('<i class="fas fa-check text-green-500"></i>');
                button.addClass('bg-green-500 hover:bg-green-600');
                form[0].reset();
                
                // Show success message
                const successMsg = $('<div class="mt-2 text-sm text-green-500">ขอบคุณที่สมัครรับข่าวสาร!</div>');
                form.append(successMsg);
                
                setTimeout(function() {
                    button.html(originalText);
                    button.prop('disabled', false);
                    button.removeClass('bg-green-500 hover:bg-green-600');
                    successMsg.fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 3000);
            }, 1500);
        });
    });

    // Footer Toggle Function (for mobile accordion) - Enhanced
    window.toggleFooter = function(button) {
        if (!button) return;
        
        const $button = $(button);
        const $icon = $button.find('i.fa-chevron-down');
        const targetId = $button.attr('aria-controls');
        const $links = targetId ? $('#' + targetId) : $button.next('.footer-links');
        
        if ($links.length) {
            const isHidden = $links.hasClass('hidden') || $button.attr('aria-expanded') === 'false';
            
            if (isHidden) {
                $links.removeClass('hidden').slideDown(300, function() {
                    $(this).attr('aria-hidden', 'false');
                });
                $icon.addClass('rotate-180');
                $button.attr('aria-expanded', 'true');
            } else {
                $links.slideUp(300, function() {
                    $(this).addClass('hidden').attr('aria-hidden', 'true');
                });
                $icon.removeClass('rotate-180');
                $button.attr('aria-expanded', 'false');
            }
        }
    };
    
    // Enhanced Newsletter Form Handler
    window.handleNewsletterSubmit = function(event) {
        event.preventDefault();
        const form = event.target.closest('form');
        if (!form) return;
        
        const emailInput = form.querySelector('input[type="email"]');
        const button = form.querySelector('button[type="submit"]');
        
        if (!emailInput || !button) return;
        
        const email = emailInput.value.trim();
        const originalButtonHTML = button.innerHTML;
        
        if (!email) {
            emailInput.focus();
            return;
        }
        
        // Disable button and show loading state
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        // Simulate API call (replace with actual API endpoint)
        setTimeout(function() {
            // Success state
            button.innerHTML = '<i class="fas fa-check text-green-500"></i>';
            button.classList.add('bg-green-500', 'hover:bg-green-600');
            emailInput.value = '';
            
            // Show success message (you can replace with toast notification)
            const successMsg = document.createElement('div');
            successMsg.className = 'mt-2 text-sm text-green-500';
            successMsg.textContent = 'ขอบคุณที่สมัครรับข่าวสาร!';
            form.appendChild(successMsg);
            
            setTimeout(function() {
                button.disabled = false;
                button.innerHTML = originalButtonHTML;
                button.classList.remove('bg-green-500', 'hover:bg-green-600');
                if (successMsg.parentNode) {
                    successMsg.parentNode.removeChild(successMsg);
                }
            }, 3000);
        }, 1500);
    };

    // Hero Slider Functionality
    function initHeroSlider() {
        const sliderContainer = document.querySelector('.hero-slider-container');
        if (!sliderContainer) return;

        const slides = sliderContainer.querySelectorAll('.hero-slide');
        const indicators = sliderContainer.querySelectorAll('.hero-slider-indicator');
        const prevBtn = sliderContainer.querySelector('.hero-slider-prev');
        const nextBtn = sliderContainer.querySelector('.hero-slider-next');
        
        if (slides.length === 0) return;

        let currentSlide = 0;
        let autoplayInterval = null;
        const autoplayDelay = 5000; // 5 seconds

        function showSlide(index) {
            // Remove active class from all slides and indicators
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(indicator => {
                indicator.classList.remove('active', 'bg-white');
                indicator.classList.add('bg-white/50');
            });

            // Add active class to current slide and indicator
            if (slides[index]) {
                slides[index].classList.add('active');
            }
            if (indicators[index]) {
                indicators[index].classList.add('active', 'bg-white');
                indicators[index].classList.remove('bg-white/50');
            }

            currentSlide = index;
        }

        function nextSlide() {
            const next = (currentSlide + 1) % slides.length;
            showSlide(next);
        }

        function prevSlide() {
            const prev = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(prev);
        }

        function startAutoplay() {
            stopAutoplay();
            autoplayInterval = setInterval(nextSlide, autoplayDelay);
        }

        function stopAutoplay() {
            if (autoplayInterval) {
                clearInterval(autoplayInterval);
                autoplayInterval = null;
            }
        }

        // Navigation button events
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                nextSlide();
                startAutoplay();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                prevSlide();
                startAutoplay();
            });
        }

        // Indicator events
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                showSlide(index);
                startAutoplay();
            });
        });

        // Pause autoplay on hover
        sliderContainer.addEventListener('mouseenter', stopAutoplay);
        sliderContainer.addEventListener('mouseleave', startAutoplay);

        // Keyboard navigation
        sliderContainer.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                prevSlide();
                startAutoplay();
            } else if (e.key === 'ArrowRight') {
                nextSlide();
                startAutoplay();
            }
        });

        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        sliderContainer.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        sliderContainer.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    nextSlide();
                } else {
                    prevSlide();
                }
                startAutoplay();
            }
        }

        // Start autoplay
        startAutoplay();
    }

    // Initialize hero slider when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHeroSlider);
    } else {
        initHeroSlider();
    }
    
    // Search overlay open/close (always run — mockup Phase 1)
    $('.chrysoberyl-search-toggle').on('click', function() {
        $('#chrysoberyl-search-modal').removeClass('hidden').attr('aria-hidden', 'false');
        $('body').addClass('chrysoberyl-search-modal-open');
        var input = document.querySelector('#chrysoberyl-search-modal .chrysoberyl-search-input');
        if (input) { input.focus(); }
    });
    $(document).on('click', '.chrysoberyl-search-close, .chrysoberyl-search-backdrop, #chrysoberyl-search-modal', function(e) {
        var $target = $(e.target);
        if (e.target === this || $target.closest('.chrysoberyl-search-close').length || $target.closest('.chrysoberyl-search-backdrop').length || !$target.closest('.chrysoberyl-search-modal-content').length) {
            $('#chrysoberyl-search-modal').addClass('hidden').attr('aria-hidden', 'true');
            $('body').removeClass('chrysoberyl-search-modal-open');
        }
    });
    $(document).on('click', '.chrysoberyl-search-modal-content', function(e) { e.stopPropagation(); });
    // Close search modal on Escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && !$('#chrysoberyl-search-modal').hasClass('hidden')) {
            $('#chrysoberyl-search-modal').addClass('hidden').attr('aria-hidden', 'true');
            $('body').removeClass('chrysoberyl-search-modal-open');
        }
    });

    // Login modal (single post — log in without leaving page)
    $(document).on('click', '.chrysoberyl-login-trigger, a[href="#chrysoberyl-login-modal"]', function(e) {
        e.preventDefault();
        var $modal = $('#chrysoberyl-login-modal');
        if (!$modal.length) return;
        var scrollY = window.pageYOffset || document.documentElement.scrollTop;
        $('#chrysoberyl-login-redirect').val(window.location.href);
        $('#chrysoberyl-login-error').addClass('hidden').empty();
        $modal.removeClass('hidden').attr('aria-hidden', 'false');
        document.body.classList.add('chrysoberyl-login-modal-open');
        document.body.style.overflow = 'hidden';
        document.body.style.top = '-' + scrollY + 'px';
        document.body.style.position = 'fixed';
        document.body.style.left = '0';
        document.body.style.right = '0';
        document.body.setAttribute('data-chrysoberyl-scroll-y', String(scrollY));
        var userInput = document.getElementById('chrysoberyl-login-user');
        if (userInput && userInput.focus) {
            userInput.focus({ preventScroll: true });
        }
    });
    function closeLoginModal() {
        var scrollY = document.body.getAttribute('data-chrysoberyl-scroll-y');
        $('#chrysoberyl-login-modal').addClass('hidden').attr('aria-hidden', 'true');
        document.body.classList.remove('chrysoberyl-login-modal-open');
        document.body.style.overflow = '';
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.left = '';
        document.body.style.right = '';
        document.body.removeAttribute('data-chrysoberyl-scroll-y');
        if (scrollY !== null && scrollY !== '') {
            window.scrollTo(0, parseInt(scrollY, 10));
        }
    }
    $(document).on('click', '.chrysoberyl-login-close, .chrysoberyl-login-backdrop', function() { closeLoginModal(); });
    $(document).on('click', '#chrysoberyl-login-modal', function(e) {
        if (e.target.id === 'chrysoberyl-login-modal') closeLoginModal();
    });
    $(document).on('click', '.chrysoberyl-login-modal-content', function(e) { e.stopPropagation(); });
    $(document).on('submit', '#chrysoberyl-login-form', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $error = $('#chrysoberyl-login-error');
        var $submit = $('#chrysoberyl-login-submit');
        $error.addClass('hidden').empty();
        $submit.prop('disabled', true);
        var ajaxurl = typeof chrysoberylAjax !== 'undefined' ? chrysoberylAjax.ajaxurl : '';
        if (!ajaxurl) { $submit.prop('disabled', false); return; }
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(res) {
                if (res && res.success && res.data && res.data.redirect) {
                    window.location.href = res.data.redirect;
                    return;
                }
                var msg = (res && res.data && res.data.message) ? res.data.message : (res && res.data && typeof res.data === 'string') ? res.data : 'Login failed.';
                $error.removeClass('hidden').text(msg).attr('role', 'alert');
                $submit.prop('disabled', false);
            },
            error: function(xhr) {
                var msg = 'Connection error.';
                if (xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message) msg = xhr.responseJSON.data.message;
                else if (xhr.responseText) {
                    try {
                        var j = JSON.parse(xhr.responseText);
                        if (j.data && j.data.message) msg = j.data.message;
                    } catch (e) {}
                }
                $error.removeClass('hidden').text(msg).attr('role', 'alert');
                $submit.prop('disabled', false);
            }
        });
    });
    // Password show/hide toggle (like wp-login)
    $(document).on('click', '#chrysoberyl-login-pwd-toggle', function() {
        var $pwd = $('#chrysoberyl-login-pwd');
        var $show = $('.chrysoberyl-pwd-icon-show');
        var $hide = $('.chrysoberyl-pwd-icon-hide');
        var $btn = $(this);
        if ($pwd.attr('type') === 'password') {
            $pwd.attr('type', 'text');
            $show.addClass('hidden');
            $hide.removeClass('hidden');
            $btn.attr('aria-label', $btn.data('hide-label') || 'Hide password').attr('title', $btn.data('hide-label') || 'Hide password');
        } else {
            $pwd.attr('type', 'password');
            $show.removeClass('hidden');
            $hide.addClass('hidden');
            $btn.attr('aria-label', $btn.data('show-label') || 'Show password').attr('title', $btn.data('show-label') || 'Show password');
        }
    });
    $(document).on('keydown', '#chrysoberyl-login-modal', function(e) {
        if (e.key === 'Escape' && !$('#chrysoberyl-login-modal').hasClass('hidden')) closeLoginModal();
    });

    // Initialize Search Functionality (AJAX suggestions etc.)
    if (typeof chrysoberylAjax !== 'undefined' && chrysoberylAjax.search && chrysoberylAjax.search.enabled) {
        initSearchFunctionality();
    }

    // Search Functionality
    function initSearchFunctionality() {
        if (typeof chrysoberylAjax === 'undefined' || !chrysoberylAjax.search) {
            return;
        }
        
        const searchConfig = chrysoberylAjax.search;
        let searchTimeout = null;
        let currentSearchTerm = '';

        // Search Input Handler
        function handleSearchInput(input) {
            const searchTerm = $(input).val().trim();
            
            if (searchTerm === currentSearchTerm) {
                return;
            }
            
            currentSearchTerm = searchTerm;
            
            // Clear previous timeout
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }
            
            const suggestionsContainer = $(input).closest('.chrysoberyl-search-container, .chrysoberyl-search-modal-content').find('.chrysoberyl-search-suggestions');
            
            if (searchTerm.length < searchConfig.min_length) {
                suggestionsContainer.addClass('hidden').empty();
                return;
            }
            
            // Debounce search
            searchTimeout = setTimeout(function() {
                if (searchConfig.suggestions_enabled) {
                    performSearch(searchTerm, suggestionsContainer);
                }
            }, searchConfig.debounce);
        }
        
        // Perform Search
        function performSearch(term, container) {
            if (typeof chrysoberylAjax === 'undefined') {
                return;
            }
            
            container.html('<div class="p-4 text-center text-gray-500"><i class="fas fa-spinner fa-spin"></i> กำลังค้นหา...</div>');
            container.removeClass('hidden');
            
            $.ajax({
                url: chrysoberylAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'search_suggestions',
                    search: term,
                    nonce: chrysoberylAjax.nonce
                },
                success: function(response) {
                    if (response.success && response.data.suggestions) {
                        renderSuggestions(response.data.suggestions, container, searchConfig);
                    } else {
                        container.html('<div class="p-4 text-center text-gray-500">ไม่พบผลการค้นหา</div>');
                    }
                },
                error: function() {
                    container.html('<div class="p-4 text-center text-red-500">เกิดข้อผิดพลาดในการค้นหา</div>');
                }
            });
        }
        
        // Render Suggestions
        function renderSuggestions(suggestions, container, config) {
            if (suggestions.length === 0) {
                container.html('<div class="p-4 text-center text-gray-500">ไม่พบผลการค้นหา</div>');
                return;
            }
            
            let html = '<div class="divide-y divide-gray-100">';
            
            suggestions.forEach(function(item) {
                html += '<a href="' + item.url + '" class="block p-4 hover:bg-gray-50 transition group">';
                html += '<div class="flex items-start gap-4">';
                
                if (item.image) {
                    html += '<img src="' + item.image + '" alt="' + item.title + '" class="w-16 h-16 object-cover rounded flex-shrink-0" />';
                }
                
                html += '<div class="flex-1 min-w-0">';
                
                if (item.category) {
                    html += '<span class="inline-block text-xs font-bold text-white px-2 py-1 rounded mb-2" style="background-color: ' + (item.category_color || '#3B82F6') + '">' + item.category + '</span>';
                }
                
                html += '<h3 class="font-bold text-gray-900 group-hover:text-accent transition line-clamp-2 mb-1">' + item.title + '</h3>';
                
                if (item.excerpt) {
                    html += '<p class="text-sm text-gray-500 line-clamp-2">' + item.excerpt + '</p>';
                }
                
                if (item.date) {
                    html += '<p class="text-xs text-gray-400 mt-2"><i class="far fa-clock mr-1"></i>' + item.date + '</p>';
                }
                
                html += '</div>';
                html += '</div>';
                html += '</a>';
            });
            
            html += '</div>';
            container.html(html);
        }
        
        // Bind search input events
        $(document).on('input', '.chrysoberyl-search-input', function() {
            if (searchConfig.live_enabled) {
                handleSearchInput(this);
            }
        });
        
        $(document).on('keydown', '.chrysoberyl-search-input', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const searchTerm = $(this).val().trim();
                if (searchTerm.length >= searchConfig.min_length) {
                    window.location.href = chrysoberylAjax.searchUrl || (window.location.origin + '/?s=') + encodeURIComponent(searchTerm);
                }
            }
        });
        
        // Search Submit Button
        $(document).on('click', '.chrysoberyl-search-submit', function() {
            const searchInput = $(this).closest('.chrysoberyl-search-modal-content, .chrysoberyl-search-container').find('.chrysoberyl-search-input');
            const searchTerm = searchInput.val().trim();
            if (searchTerm.length >= searchConfig.min_length) {
                window.location.href = chrysoberylAjax.searchUrl || (window.location.origin + '/?s=') + encodeURIComponent(searchTerm);
            }
        });
        
        // Close suggestions when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.chrysoberyl-search-container, .chrysoberyl-search-modal').length) {
                $('.chrysoberyl-search-suggestions').addClass('hidden');
            }
        });
    }
    
    // Initialize Table of Contents
    initTableOfContents();

})(jQuery);

// Table of Contents Functionality
function initTableOfContents() {
    const tocContainer = document.querySelector('.chrysoberyl-toc');
    if (!tocContainer) {
        return;
    }
    
    try {
        const config = JSON.parse(tocContainer.getAttribute('data-toc-config') || '{}') || {};
        const headingsSelector = (config.headings || 'h2,h3,h4').split(',').map(h => h.trim()).join(', ');
        
        // TOC toggle: document-level delegation in capture phase so it works on page single (TOC in footer)
        if (!document.body.hasAttribute('data-chrysoberyl-toc-delegate')) {
            document.body.setAttribute('data-chrysoberyl-toc-delegate', '1');
            document.body.addEventListener('click', function(e) {
                var toggleBtn = e.target.closest('.chrysoberyl-toc-toggle');
                if (!toggleBtn) return;
                var toc = toggleBtn.closest('.chrysoberyl-toc');
                if (!toc) return;
                e.preventDefault();
                e.stopPropagation();
                var content = toc.querySelector('.chrysoberyl-toc-content');
                var icon = toggleBtn.querySelector('.chrysoberyl-toc-icon');
                if (content) {
                    content.classList.toggle('chrysoberyl-toc-content-collapsed');
                    if (icon) {
                        icon.classList.toggle('fa-chevron-down');
                        icon.classList.toggle('fa-chevron-up');
                    }
                }
            }, true);
        }
        var mobileDrawer = document.querySelector('.chrysoberyl-toc-mobile-drawer');
        var mobileToggle = document.querySelector('.chrysoberyl-toc-mobile-toggle');
        var mobileClose = document.querySelector('.chrysoberyl-toc-mobile-close');
        if (mobileToggle && mobileDrawer) {
            mobileToggle.addEventListener('click', function() {
                mobileDrawer.classList.remove('hidden');
                var content = mobileDrawer.querySelector('.chrysoberyl-toc-mobile-content');
                if (content) setTimeout(function() { content.classList.add('chrysoberyl-toc-mobile-content-open'); }, 10);
            });
        }
        if (mobileClose && mobileDrawer) {
            mobileClose.addEventListener('click', function() {
                var content = mobileDrawer.querySelector('.chrysoberyl-toc-mobile-content');
                if (content) content.classList.remove('chrysoberyl-toc-mobile-content-open');
                setTimeout(function() { mobileDrawer.classList.add('hidden'); }, 300);
            });
        }
        if (mobileDrawer) {
            mobileDrawer.addEventListener('click', function(e) {
                if (e.target === mobileDrawer) {
                    var content = mobileDrawer.querySelector('.chrysoberyl-toc-mobile-content');
                    if (content) content.classList.remove('chrysoberyl-toc-mobile-content-open');
                    setTimeout(function() { mobileDrawer.classList.add('hidden'); }, 300);
                }
            });
        }
        
        // Find the main article content area - prioritize .chrysoberyl-article-content
        let contentArea = document.querySelector('.chrysoberyl-article-content');
        if (!contentArea) {
            contentArea = document.querySelector('#article-content .prose, .prose[data-toc-content="true"], #article-content');
        }
        if (!contentArea) {
            contentArea = document.querySelector('#main-content [data-toc-content="true"], main [data-toc-content="true"]');
        }
        if (!contentArea) {
            contentArea = document.querySelector('.prose, .entry-content');
        }
        
        if (!contentArea) {
            return;
        }
        
        // Find all headings ONLY within the main article content
        const allHeadings = Array.from(contentArea.querySelectorAll(headingsSelector));
        
        // Filter out headings from excluded sections
        const headings = allHeadings.filter(heading => {
            // Exclude headings that are inside elements with data-toc-exclude attribute
            const excludedParent = heading.closest('[data-toc-exclude="true"]');
            if (excludedParent) {
                return false;
            }
            
            // Exclude headings from related posts section
            const relatedSection = heading.closest('[class*="mt-16"], [class*="related"], [id*="related"]');
            if (relatedSection && (relatedSection.querySelector('a[href*="post"]') || relatedSection.textContent.includes('เกี่ยวข้อง'))) {
                return false;
            }
            
            // Exclude headings from comments section
            const commentsSection = heading.closest('#comments, .comments-area, .comment-form, [class*="comment"], [id*="comment"]');
            if (commentsSection) {
                return false;
            }
            
            // Exclude headings from widget areas
            const widgetArea = heading.closest('.widget, [class*="widget"], [id*="sidebar"]');
            if (widgetArea) {
                return false;
            }
            
            // Exclude headings from tags section
            const tagsSection = heading.closest('[class*="mt-10"]');
            if (tagsSection && tagsSection.querySelector('a[href*="tag"]')) {
                return false;
            }
            
            // Exclude headings from after-content widget area
            const afterContent = heading.closest('[id*="after-content"], [class*="after-content"]');
            if (afterContent) {
                return false;
            }
            
            // Exclude headings that are outside the main article content
            if (!contentArea.contains(heading)) {
                return false;
            }
            
            // Exclude specific headings by text content
            const headingText = heading.textContent.trim().toLowerCase();
            const excludedTexts = [
                'ข่าวที่เกี่ยวข้อง',
                'related',
                'leave a reply',
                'ความคิดเห็น',
                'comments',
                'tags',
                'tag',
                'related posts',
                'related news',
                'related videos',
                'related galleries',
                'reply',
                'ความคิดเห็น',
                'comment',
                'reply to',
                'cancel reply'
            ];
            
            if (excludedTexts.some(text => headingText.includes(text))) {
                return false;
            }
            
            return true;
        });
        
        // Check minimum headings count
        if (headings.length < config.minHeadings) {
            tocContainer.style.display = 'none';
            const mobileToggle = document.querySelector('.chrysoberyl-toc-mobile-toggle');
            if (mobileToggle) {
                mobileToggle.style.display = 'none';
            }
            return;
        }
        
        // Generate anchor IDs for headings
        headings.forEach((heading, index) => {
            if (!heading.id) {
                const text = heading.textContent.trim();
                const id = 'toc-' + text.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .substring(0, 50) + '-' + index;
                heading.id = id;
            }
        });
        
        // Generate TOC structure
        const tocNav = tocContainer.querySelector('.chrysoberyl-toc-nav');
        const mobileTocNav = document.querySelector('.chrysoberyl-toc-mobile-nav');
        
        if (tocNav) {
            tocNav.innerHTML = generateTOC(headings, config.style);
        }
        
        if (mobileTocNav) {
            mobileTocNav.innerHTML = generateTOC(headings, config.style);
        }
        
        // Smooth scroll
        if (config.smoothScroll) {
            if (tocNav) {
                tocNav.addEventListener('click', function(e) {
                    const link = e.target.closest('a[href^="#"]');
                    if (link) {
                        e.preventDefault();
                        const targetId = link.getAttribute('href').substring(1);
                        const targetElement = document.getElementById(targetId);
                        if (targetElement) {
                            const offset = 100;
                            const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - offset;
                            window.scrollTo({
                                top: targetPosition,
                                behavior: 'smooth'
                            });
                        }
                    }
                });
            }
            
            if (mobileTocNav) {
                mobileTocNav.addEventListener('click', function(e) {
                    const link = e.target.closest('a[href^="#"]');
                    if (link) {
                        e.preventDefault();
                        const targetId = link.getAttribute('href').substring(1);
                        const targetElement = document.getElementById(targetId);
                        if (targetElement) {
                            const offset = 100;
                            const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - offset;
                            window.scrollTo({
                                top: targetPosition,
                                behavior: 'smooth'
                            });
                            // Close mobile drawer
                            const drawer = document.querySelector('.chrysoberyl-toc-mobile-drawer');
                            if (drawer) {
                                const content = drawer.querySelector('.chrysoberyl-toc-mobile-content');
                                if (content) {
                                    content.classList.remove('chrysoberyl-toc-mobile-content-open');
                                    setTimeout(() => drawer.classList.add('hidden'), 300);
                                }
                            }
                        }
                    }
                });
            }
        }
        
        // Scroll Spy
        if (config.scrollSpy) {
            let scrollTimeout;
            window.addEventListener('scroll', function() {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(function() {
                    updateActiveSection(headings, tocNav, mobileTocNav);
                }, 100);
            });
            updateActiveSection(headings, tocNav, mobileTocNav);
        }
        
        // Auto-collapse on mobile
        if (config.autoCollapseMobile && window.innerWidth < 768) {
            const content = tocContainer.querySelector('.chrysoberyl-toc-content');
            if (content) {
                content.classList.add('chrysoberyl-toc-content-collapsed');
            }
        }
        
    } catch (error) {
        console.error('TOC initialization error:', error);
    }
}

// Generate TOC HTML
function generateTOC(headings, style) {
    if (headings.length === 0) {
        return '<p class="text-gray-500 text-sm">ไม่พบหัวข้อ</p>';
    }
    
    let html = '';
    let currentLevel = 0;
    let itemNumber = 0;
    
    let minLevel = Math.min(...headings.map(h => parseInt(h.tagName.substring(1))));
    
    headings.forEach((heading, index) => {
        const level = parseInt(heading.tagName.substring(1));
        const id = heading.id || 'toc-' + index;
        const text = heading.textContent.trim();
        const isTopLevel = (level === minLevel);
        
        if (level > currentLevel) {
            // Open new nested list - use chrysoberyl-toc-nested for sub-items
            for (let i = currentLevel; i < level - 1; i++) {
                html += '<ul class="chrysoberyl-toc-nested">';
            }
            // First list or nested list
            html += currentLevel === 0 ? '<ul class="chrysoberyl-toc-list">' : '<ul class="chrysoberyl-toc-nested">';
            currentLevel = level;
        } else if (level < currentLevel) {
            // Close nested lists
            for (let i = currentLevel; i > level; i--) {
                html += '</ul>';
            }
            currentLevel = level;
        }
        
        itemNumber++;
        const number = style === 'numbered' ? itemNumber + '. ' : '';
        // Always add level class for styling hierarchy
        const levelClass = 'chrysoberyl-toc-item-level-' + level;
        const topLevelClass = isTopLevel ? 'chrysoberyl-toc-item-main' : 'chrysoberyl-toc-item-sub';
        
        html += '<li class="chrysoberyl-toc-item ' + levelClass + ' ' + topLevelClass + '">';
        html += '<a href="#' + id + '" class="chrysoberyl-toc-link" data-toc-id="' + id + '">';
        html += number + text;
        html += '</a>';
        html += '</li>';
    });
    
    // Close remaining lists
    for (let i = currentLevel; i > 0; i--) {
        html += '</ul>';
    }
    
    return html;
}

// Update active section in TOC
function updateActiveSection(headings, tocNav, mobileTocNav) {
    if (!tocNav && !mobileTocNav) {
        return;
    }
    
    const scrollPosition = window.pageYOffset + 150;
    let activeId = null;
    
    // Find the current active heading
    for (let i = headings.length - 1; i >= 0; i--) {
        const heading = headings[i];
        const rect = heading.getBoundingClientRect();
        if (rect.top <= 150) {
            activeId = heading.id;
            break;
        }
    }
    
    // Update active state
    [tocNav, mobileTocNav].forEach(nav => {
        if (!nav) return;
        
        nav.querySelectorAll('.chrysoberyl-toc-link').forEach(link => {
            link.classList.remove('chrysoberyl-toc-link-active');
        });
        
        if (activeId) {
            const activeLink = nav.querySelector('a[href="#' + activeId + '"]');
            if (activeLink) {
                activeLink.classList.add('chrysoberyl-toc-link-active');
                // Scroll into view if needed (only for sidebar)
                if (nav.closest('.chrysoberyl-toc-sidebar')) {
                    activeLink.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }
        }
    });
}
