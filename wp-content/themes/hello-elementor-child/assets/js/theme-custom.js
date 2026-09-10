/**
 * Custom JavaScript for Hello Elementor Child
 */
document.addEventListener('DOMContentLoaded', function () {
  // 1. Mobile Menu Toggle
  var mobileToggle = document.getElementById('ktdMobileToggle');
  var mobileDrawer = document.getElementById('ktdMobileDrawer');

  if (mobileToggle && mobileDrawer) {
    mobileToggle.addEventListener('click', function () {
      var isOpen = mobileDrawer.classList.toggle('open');
      mobileToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function (e) {
      if (!mobileToggle.contains(e.target) && !mobileDrawer.contains(e.target)) {
        mobileDrawer.classList.remove('open');
        mobileToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // 2. Floating Navigation (Scroll to top & Home)
  var floatingNav = document.getElementById('ktdFloatingNav');
  var scrollTopBtn = document.getElementById('ktdScrollTopBtn');

  if (floatingNav) {
    var handleScroll = function () {
      if (window.scrollY > 300) {
        floatingNav.classList.add('visible');
      } else {
        floatingNav.classList.remove('visible');
      }
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll(); // Initial check
  }

  if (scrollTopBtn) {
    scrollTopBtn.addEventListener('click', function (e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }

  // 3. Mobile Shop Filter Toggle
  var filterToggle = document.getElementById('ktdFilterToggle');
  var shopSidebar = document.getElementById('ktdShopSidebar');
  if (filterToggle && shopSidebar) {
    filterToggle.addEventListener('click', function () {
      shopSidebar.classList.toggle('open');
      filterToggle.classList.toggle('active');
    });
  }

  // 4. Cart & Single Product Quantity Buttons (+ / -)
  function initCartQtyButtons() {
    var qtyWrappers = document.querySelectorAll('.woocommerce .quantity:not(.ktd-qty-ready), .product .quantity:not(.ktd-qty-ready)');
    qtyWrappers.forEach(function (wrap) {
      var input = wrap.querySelector('input.qty');
      if (!input || input.type === 'hidden') return;

      wrap.classList.add('ktd-qty-ready');

      // Minus Button
      var minusBtn = document.createElement('button');
      minusBtn.type = 'button';
      minusBtn.className = 'ktd-qty-btn ktd-qty-minus';
      minusBtn.textContent = '−';
      minusBtn.setAttribute('aria-label', 'Giảm số lượng');

      // Plus Button
      var plusBtn = document.createElement('button');
      plusBtn.type = 'button';
      plusBtn.className = 'ktd-qty-btn ktd-qty-plus';
      plusBtn.textContent = '+';
      plusBtn.setAttribute('aria-label', 'Tăng số lượng');

      wrap.insertBefore(minusBtn, input);
      wrap.appendChild(plusBtn);

      minusBtn.addEventListener('click', function () {
        var min = parseFloat(input.getAttribute('min')) || 1;
        var step = parseFloat(input.getAttribute('step')) || 1;
        var current = parseFloat(input.value) || 1;
        if (current > min) {
          input.value = current - step;
          triggerChange(input);
        }
      });

      plusBtn.addEventListener('click', function () {
        var max = parseFloat(input.getAttribute('max')) || Infinity;
        var step = parseFloat(input.getAttribute('step')) || 1;
        var current = parseFloat(input.value) || 1;
        if (current < max) {
          input.value = current + step;
          triggerChange(input);
        }
      });
    });
  }

  var cartUpdateTimer;
  function triggerChange(input) {
    var event = new Event('change', { bubbles: true });
    input.dispatchEvent(event);
    var updateBtn = document.querySelector('button[name="update_cart"]');
    if (updateBtn) {
      updateBtn.disabled = false;
      updateBtn.setAttribute('aria-disabled', 'false');

      clearTimeout(cartUpdateTimer);
      cartUpdateTimer = setTimeout(function () {
        if (updateBtn && !updateBtn.disabled) {
          updateBtn.click();
        }
      }, 600);
    }
  }

  initCartQtyButtons();

  // Re-run on WooCommerce cart updated via AJAX
  if (window.jQuery) {
    window.jQuery(document.body).on('updated_cart_totals', function () {
      initCartQtyButtons();
    });
  }

  // 5. Dual-Handle Price Slider
  function initDualPriceSlider() {
    var sliderWrap = document.getElementById('ktdDualSliderWrap');
    if (!sliderWrap) return;

    var rangeMin = document.getElementById('ktdRangeMin');
    var rangeMax = document.getElementById('ktdRangeMax');
    var highlight = document.getElementById('ktdSliderBarHighlight');
    var labelMin = document.getElementById('ktdPriceMinLabel');
    var labelMax = document.getElementById('ktdPriceMaxLabel');
    var applyBtn = document.getElementById('ktdPriceApplyBtn');

    if (!rangeMin || !rangeMax || !highlight || !labelMin || !labelMax) return;

    var totalMin = parseFloat(sliderWrap.getAttribute('data-min')) || 0;
    var totalMax = parseFloat(sliderWrap.getAttribute('data-max')) || 50000000;
    var step = parseFloat(sliderWrap.getAttribute('data-step')) || 500000;

    function formatVND(amount) {
      return new Intl.NumberFormat('vi-VN').format(amount) + ' ₫';
    }

    function updateSlider(e) {
      var minVal = parseFloat(rangeMin.value);
      var maxVal = parseFloat(rangeMax.value);

      if (e && e.target === rangeMin) {
        if (minVal > maxVal - step) {
          rangeMin.value = maxVal - step;
          minVal = maxVal - step;
        }
      } else if (e && e.target === rangeMax) {
        if (maxVal < minVal + step) {
          rangeMax.value = minVal + step;
          maxVal = minVal + step;
        }
      }

      var percentMin = ((minVal - totalMin) / (totalMax - totalMin)) * 100;
      var percentMax = ((maxVal - totalMin) / (totalMax - totalMin)) * 100;

      highlight.style.left = percentMin + '%';
      highlight.style.width = (percentMax - percentMin) + '%';

      labelMin.textContent = formatVND(minVal);
      labelMax.textContent = formatVND(maxVal);
    }

    rangeMin.addEventListener('input', updateSlider);
    rangeMax.addEventListener('input', updateSlider);

    // Initial positioning & values
    updateSlider();

    if (applyBtn) {
      applyBtn.addEventListener('click', function () {
        var minVal = rangeMin.value;
        var maxVal = rangeMax.value;
        var currentUrl = new URL(window.location.href);

        currentUrl.searchParams.set('min_price', minVal);
        currentUrl.searchParams.set('max_price', maxVal);

        // Reset to page 1 if paginated
        currentUrl.pathname = currentUrl.pathname.replace(/\/page\/\d+\/?$/, '/');

        window.location.href = currentUrl.toString();
      });
    }
  }

  initDualPriceSlider();

  // 6. Product Collapsible Article (HoangHaMobile / ClickBuy style)
  function initProductCollapsibleArticle() {
    var container = document.getElementById('ktdArticleCollapsible');
    var body = document.getElementById('ktdArticleBody');
    var toggleBtn = document.getElementById('ktdToggleArticleBtn');
    var detailSection = document.getElementById('ktdProductDetailSection');

    if (!container || !body || !toggleBtn) return;

    var COLLAPSED_HEIGHT = 620;

    function checkHeight() {
      var fullHeight = body.scrollHeight || body.offsetHeight;
      if (fullHeight > COLLAPSED_HEIGHT) {
        if (!container.classList.contains('is-expanded')) {
          container.classList.add('is-collapsed');
        }
        toggleBtn.style.display = 'inline-flex';
      } else {
        container.classList.remove('is-collapsed', 'is-expanded');
        toggleBtn.style.display = 'none';
      }
    }

    checkHeight();
    window.addEventListener('load', checkHeight);

    toggleBtn.addEventListener('click', function () {
      var isCollapsed = container.classList.contains('is-collapsed');
      var btnText = toggleBtn.querySelector('.ktd-btn-text');

      if (isCollapsed) {
        container.classList.remove('is-collapsed');
        container.classList.add('is-expanded');
        toggleBtn.setAttribute('aria-expanded', 'true');
        if (btnText) btnText.textContent = 'Thu gọn nội dung';
      } else {
        container.classList.remove('is-expanded');
        container.classList.add('is-collapsed');
        toggleBtn.setAttribute('aria-expanded', 'false');
        if (btnText) btnText.textContent = 'Đọc tiếp bài viết';

        if (detailSection) {
          var topPos = detailSection.getBoundingClientRect().top + window.pageYOffset - 90;
          window.scrollTo({
            top: topPos,
            behavior: 'smooth'
          });
        }
      }
    });
  }
  initProductCollapsibleArticle();

  // 7. Product Specifications Modal Popup
  function initProductSpecsModal() {
    var openBtn = document.getElementById('ktdOpenSpecsModal');
    var modal = document.getElementById('ktdSpecsModal');
    var closeBtn = document.getElementById('ktdCloseSpecsModal');
    var overlay = document.getElementById('ktdSpecsModalOverlay');
    var dismissBtn = document.getElementById('ktdDismissSpecsModal');

    if (!modal) return;

    function openModal() {
      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('modal-open');
    }

    function closeModal() {
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('modal-open');
    }

    if (openBtn) {
      openBtn.addEventListener('click', openModal);
    }
    if (closeBtn) {
      closeBtn.addEventListener('click', closeModal);
    }
    if (overlay) {
      overlay.addEventListener('click', closeModal);
    }
    if (dismissBtn) {
      dismissBtn.addEventListener('click', closeModal);
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && modal.classList.contains('is-open')) {
        closeModal();
      }
    });
  }
  initProductSpecsModal();

  // 8. Interactive Variation Swatches (HoangHaMobile / ClickBuy style)
  function initVariationSwatches() {
    var variationForm = document.querySelector('form.variations_form');
    if (!variationForm) return;

    var swatchWrappers = variationForm.querySelectorAll('.ktd-swatch-pills-wrap');
    var topPrice = document.querySelector('.summary.entry-summary p.price, .summary .price');

    // Parse all variations data from attribute
    var variationsData = [];
    try {
      var rawData = variationForm.getAttribute('data-product_variations');
      if (rawData) {
        variationsData = JSON.parse(rawData);
      }
    } catch (e) {
      variationsData = [];
    }

    function formatVNDPrice(amount) {
      if (!amount) return '';
      try {
        return new Intl.NumberFormat('vi-VN').format(amount).replace(/\u00a0/g, ' ') + ' ₫';
      } catch (e) {
        return amount + ' ₫';
      }
    }

    // Cập nhật khối giá duy nhất ở trên đỉnh khi chọn biến thể
    function updateMainPrice(priceHtml, numericPrice) {
      if (!topPrice) return;
      if (priceHtml && priceHtml.trim() !== '') {
        topPrice.innerHTML = priceHtml;
      } else if (numericPrice) {
        topPrice.innerHTML = '<span class="woocommerce-Price-amount amount">' + formatVNDPrice(numericPrice) + '</span>';
      }

      var stickyPrice = document.querySelector('.ktd-sticky-price');
      if (stickyPrice) {
        if (priceHtml && priceHtml.trim() !== '') {
          stickyPrice.innerHTML = priceHtml;
        } else if (numericPrice) {
          stickyPrice.innerHTML = '<span class="woocommerce-Price-amount amount">' + formatVNDPrice(numericPrice) + '</span>';
        }
      }
    }

    // Đồng bộ giá tiền hiển thị bên trong các nút swatch theo cặp Dung lượng / Màu sắc
    function updateSwatchPillPrices() {
      if (!variationsData || !variationsData.length) return;

      var capacitySelect = variationForm.querySelector('select[name="attribute_pa_dung-luong"]');
      var colorSelect = variationForm.querySelector('select[name="attribute_pa_mau-sac"]');

      var currentCapacity = capacitySelect ? capacitySelect.value : '';
      var currentColor = colorSelect ? colorSelect.value : '';

      // Cập nhật giá các nút màu sắc theo dung lượng đang chọn
      if (currentCapacity) {
        var colorWrap = variationForm.querySelector('.ktd-swatch-pills-wrap[data-attribute="pa_mau-sac"]');
        if (colorWrap) {
          var colorPills = colorWrap.querySelectorAll('.ktd-swatch-pill');
          colorPills.forEach(function (pill) {
            var cVal = pill.getAttribute('data-value');
            var matched = variationsData.find(function (v) {
              return v.attributes &&
                v.attributes['attribute_pa_dung-luong'] === currentCapacity &&
                v.attributes['attribute_pa_mau-sac'] === cVal;
            });
            if (matched && matched.display_price) {
              var priceSpan = pill.querySelector('.ktd-swatch-price');
              if (priceSpan) {
                priceSpan.textContent = formatVNDPrice(matched.display_price);
              }
            }
          });
        }
      }

      // Cập nhật giá các nút dung lượng theo màu sắc đang chọn
      if (currentColor) {
        var capacityWrap = variationForm.querySelector('.ktd-swatch-pills-wrap[data-attribute="pa_dung-luong"]');
        if (capacityWrap) {
          var capPills = capacityWrap.querySelectorAll('.ktd-swatch-pill');
          capPills.forEach(function (pill) {
            var capVal = pill.getAttribute('data-value');
            var matched = variationsData.find(function (v) {
              return v.attributes &&
                v.attributes['attribute_pa_dung-luong'] === capVal &&
                v.attributes['attribute_pa_mau-sac'] === currentColor;
            });
            if (matched && matched.display_price) {
              var priceSpan = pill.querySelector('.ktd-swatch-price');
              if (priceSpan) {
                priceSpan.textContent = formatVNDPrice(matched.display_price);
              }
            }
          });
        }
      }
    }

    swatchWrappers.forEach(function (wrap) {
      var select = wrap.parentElement ? wrap.parentElement.querySelector('select') : null;
      if (!select) return;

      var pills = wrap.querySelectorAll('.ktd-swatch-pill');

      pills.forEach(function (pill) {
        pill.addEventListener('click', function (e) {
          e.preventDefault();
          var val = pill.getAttribute('data-value');

          if (pill.classList.contains('active')) {
            return;
          }

          pills.forEach(function (p) {
            p.classList.remove('active');
            p.setAttribute('aria-pressed', 'false');
          });
          pill.classList.add('active');
          pill.setAttribute('aria-pressed', 'true');

          select.value = val;
          var changeEvent = new Event('change', { bubbles: true });
          select.dispatchEvent(changeEvent);

          if (window.jQuery) {
            window.jQuery(select).trigger('change');
          }

          updateSwatchPillPrices();
        });
      });

      function syncFromSelect() {
        var currentVal = select.value;
        if (currentVal) {
          pills.forEach(function (p) {
            var isMatch = (p.getAttribute('data-value') === currentVal);
            p.classList.toggle('active', isMatch);
            p.setAttribute('aria-pressed', isMatch ? 'true' : 'false');
          });
        }
      }

      select.addEventListener('change', syncFromSelect);
      syncFromSelect();
    });

    if (window.jQuery) {
      window.jQuery(variationForm).on('found_variation', function (event, variation) {
        if (variation) {
          updateMainPrice(variation.price_html, variation.display_price);
          updateSwatchPillPrices();
        }
      });

      window.jQuery(variationForm).on('reset_data', function () {
        swatchWrappers.forEach(function (wrap) {
          var select = wrap.parentElement ? wrap.parentElement.querySelector('select') : null;
          if (!select) return;
          var currentVal = select.value;
          var pills = wrap.querySelectorAll('.ktd-swatch-pill');
          pills.forEach(function (p) {
            var isMatch = currentVal ? (p.getAttribute('data-value') === currentVal) : false;
            p.classList.toggle('active', isMatch);
            p.setAttribute('aria-pressed', isMatch ? 'true' : 'false');
          });
        });
      });
    }

    updateSwatchPillPrices();
  }
  initVariationSwatches();

  /* -------------------------------------------------------------
   * Mobile Sticky Add to Cart Bar
   * ----------------------------------------------------------- */
  function initMobileStickyBar() {
    var stickyBar = document.getElementById('ktdMobileStickyBar');
    var mainBtn = document.querySelector('.single_add_to_cart_button');
    if (!stickyBar || !mainBtn) return;

    var stickyBuyBtn = document.getElementById('ktdStickyBuyBtn');
    if (stickyBuyBtn) {
      stickyBuyBtn.addEventListener('click', function (e) {
        e.preventDefault();
        var form = document.querySelector('form.cart');
        if (form && form.classList.contains('variations_form')) {
          var unselected = form.querySelectorAll('select[data-attribute_name]');
          var hasEmpty = false;
          for (var i = 0; i < unselected.length; i++) {
            if (!unselected[i].value) {
              hasEmpty = true;
              break;
            }
          }
          if (hasEmpty) {
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
          }
        }
        mainBtn.click();
      });
    }

    function checkStickyVisibility() {
      var rect = mainBtn.getBoundingClientRect();
      if (rect.bottom < 0) {
        stickyBar.classList.add('is-visible');
      } else {
        stickyBar.classList.remove('is-visible');
      }
    }

    if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(function (entries) {
        checkStickyVisibility();
      }, { threshold: 0 });
      observer.observe(mainBtn);
    }

    window.addEventListener('scroll', checkStickyVisibility, { passive: true });
    window.addEventListener('resize', checkStickyVisibility, { passive: true });
  }
  initMobileStickyBar();
});
