/* ============================================================
   MAIN JAVASCRIPT FILE
   ------------------------------------------------------------
   Handles:
   → User dropdown & modal handling
   → Navigation hover menu
   → Accordion toggle
   → Price filter
   → Pagination
=============================================================== */

document.addEventListener("DOMContentLoaded", function () {
  // ===== USER DROPDOWN & MODAL HANDLING =====
  const btn = document.getElementById('userDropdownBtn');
  const menu = document.getElementById('userDropdownMenu');

  if (btn && menu) {
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', function (e) {
      if (!btn.contains(e.target) && !menu.contains(e.target)) {
        menu.style.display = 'none';
      }
    });
  }

  // open modal
  document.querySelectorAll('.open-modal').forEach(el => {
    el.addEventListener('click', function (e) {
      e.preventDefault();
      const modalId = this.getAttribute('data-modal');
      const modal = document.getElementById(modalId);
      if (modal) {
        modal.style.display = 'block';
      }
      if (menu) menu.style.display = 'none';
    });
  });

  // close modal
  document.querySelectorAll('.dropdown_modalTop-close').forEach(btn => {
    btn.addEventListener('click', function () {
      const modal = this.closest('.dropdown_modalTop');
      if (modal) {
        modal.style.display = 'none';
      }
    });
  });

  // click outside to close modal
  window.addEventListener('click', function (e) {
    document.querySelectorAll('.dropdown_modalTop').forEach(modal => {
      if (e.target === modal) modal.style.display = 'none';
    });
  });

  // ===== NAVIGATION HOVER MENU =====
  const mainLinks = document.querySelectorAll(".mainlink");
  const menuBox = document.getElementById("nav-main-menu-dropdown");
  const tabPanes = document.querySelectorAll(".tab-pane");

  let hideTimer;

  if (mainLinks.length && menuBox) {
    // 👉 CLICK par menu open hoga
    mainLinks.forEach(link => {
      link.addEventListener("click", function (e) {
        e.preventDefault();
        
        clearTimeout(hideTimer);

        const target = this.dataset.menu;
        const tab = document.getElementById(target);
        if (!tab) return;

        tabPanes.forEach(p => p.classList.remove("active", "show"));
        tab.classList.add("active", "show");

        menuBox.classList.add("show");
        menuBox.style.display = "block";
      });
    });

    // 👉 Menu ke andar mouse ho to close na ho
    menuBox.addEventListener("mouseenter", () => {
      clearTimeout(hideTimer);
    });

    // 👉 Cursor bahar gaya to menu band
    menuBox.addEventListener("mouseleave", () => {
      hideTimer = setTimeout(() => {
        menuBox.classList.remove("show");
        menuBox.style.display = "none";
        tabPanes.forEach(p => p.classList.remove("active", "show"));
      }, 200);
    });
  }

  // ===== ACCORDION TOGGLE =====
  document.querySelectorAll(".accordion, .sub-accordion").forEach((btn) => {
    btn.addEventListener("click", function () {
      this.classList.toggle("active");
      const panel = this.nextElementSibling;
      if (panel) {
        panel.style.display = panel.style.display === "block" ? "none" : "block";
      }
    });
  });

  // ===== PRICE FILTER =====
  const priceRange = document.getElementById("priceRange");
  const priceValue = document.getElementById("priceValue");
  const products = document.querySelectorAll(".product");

  if (priceRange && priceValue && products.length) {
    priceRange.addEventListener("input", function () {
      const maxPrice = parseInt(this.value) || 0;
      priceValue.textContent = maxPrice;

      products.forEach((product) => {
        const price = parseInt(product.getAttribute("data-price")) || 0;
        product.style.display = price <= maxPrice ? "block" : "none";
      });
    });
  }

  // ===== PAGINATION =====
  const cards = document.querySelectorAll(".product-card");
  const pagination = document.getElementById("pagination");
  const perPage = 9;
  
  if (cards.length && pagination) {
    const totalPages = Math.ceil(cards.length / perPage);
    let currentPage = 1;

    function showPage(page) {
      const start = (page - 1) * perPage;
      const end = start + perPage;
      cards.forEach((product, index) => {
        product.style.display = index >= start && index < end ? "block" : "none";
      });
      updatePagination(page);
    }

    function updatePagination(activePage) {
      if (totalPages <= 1) return;
      
      pagination.innerHTML = "";

      // Previous button
      if (totalPages > 1) {
        const prevBtn = document.createElement("button");
        prevBtn.innerHTML = '<span class="arrow">‹</span> Prev';
        prevBtn.disabled = activePage === 1;
        prevBtn.addEventListener('click', () => showPage(activePage - 1));
        pagination.appendChild(prevBtn);
      }

      // Page numbers
      for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement("button");
        btn.textContent = i;
        btn.classList.toggle("active", i === activePage);
        btn.addEventListener('click', () => showPage(i));
        pagination.appendChild(btn);
      }

      // Next button
      if (totalPages > 1) {
        const nextBtn = document.createElement("button");
        nextBtn.innerHTML = 'Next <span class="arrow">›</span>';
        nextBtn.disabled = activePage === totalPages;
        nextBtn.addEventListener('click', () => showPage(activePage + 1));
        pagination.appendChild(nextBtn);
      }
    }

    showPage(currentPage);
  }
});

/* ============================================================
   OWL CAROUSELS & AJAX SEARCH (jQuery)
=============================================================== */
$(document).ready(function () {
  // Home slider
  if ($(".homeslider").length) {
    $(".homeslider").owlCarousel({
      loop: true,
      margin: 10,
      nav: true,
      dots: true,
      autoplay: true,
      autoplayHoverPause: true,
      autoplayTimeout: 4000,
      smartSpeed: 600,
      navSpeed: 1000,
      // animateOut: "fadeOut",
      navText: ["<span>‹</span>", "<span>›</span>"],
      responsive: { 
        0: { items: 1 }, 
        600: { items: 1 }, 
        1000: { items: 1 } 
      }
    });
  }

  // Arrivals slider
  if ($(".arrivals-slider").length) {
    $(".arrivals-slider").owlCarousel({
      margin: 25,
      loop: true,
      autoplay: true,
      autoplayHoverPause: true,
      nav: true,
      navText: ["<span>‹</span>", "<span>›</span>"],
      dots: false,
      responsive: {
        0: { items: 2 },
        576: { items: 2 },
        768: { items: 3 },
        992: { items: 5 },
        1200: { items: 5 }
      }
    });
  }

  // Trend slider
  if ($(".trend-slider").length) {
    $(".trend-slider").owlCarousel({
      margin: 10,
      loop: true,
      autoplay: true,
      autoplayHoverPause: true,
      nav: true,
      navText: ["<span>‹</span>", "<span>›</span>"],
      dots: false,
      responsive: {
        0: { items: 1 },
        576: { items: 1 },
        768: { items: 2 },
        992: { items: 4 },
        1200: { items: 4 }
      }
    });
  }

  // Reviews slider
  if ($(".allpublicreview").length) {
    $(".allpublicreview").owlCarousel({
      loop: true,
      margin: 30,
      nav: false,
      dots: true,
      autoplay: true,
      autoplayHoverPause: true,
      autoplayTimeout: 4000,
      smartSpeed: 800,
      responsive: {
        0: { items: 2 },
        768: { items: 3 },
        1200: { items: 3 }
      }
    });
  }


  
  // ===== SEARCH + MODAL =====
  let modalOpen = false;

  $(document).on('click', '.open-modal', function (e) {
    e.preventDefault();
    const modalId = $(this).data('modal');
    $('#' + modalId).fadeIn(200);
    modalOpen = true;
    $('#searchProduct').prop('disabled', true);
  });

  $(document).on('click', '.dropdown_modalTop-close', function () {
    $(this).closest('.dropdown_modalTop').fadeOut(200);
    modalOpen = false;
    $('#searchProduct').prop('disabled', false);
  });

  $(document).on('click', '.dropdown_modalTop', function (e) {
    if ($(e.target).hasClass('dropdown_modalTop')) {
      $(this).fadeOut(200);
      modalOpen = false;
      $('#searchProduct').prop('disabled', false);
    }
  });

  // Search functionality
  const searchInput = $('#searchProduct');
  if (searchInput.length) {
    let searchTimeout;
    
    searchInput.on('keyup', function () {
      if (modalOpen) return;
      
      clearTimeout(searchTimeout);
      const query = $(this).val().trim();

      if (query.length < 2) {
        $('#searchResults').hide().html('');
        return;
      }

      searchTimeout = setTimeout(() => {

    if (!query || query.trim() === "") {
        $('#searchResults').hide();
        return;
    }

    // 🔥 Previous request cancel karo
    if (searchRequest) {
        searchRequest.abort();
    }

    searchRequest = $.ajax({
          url: "{{ route('product.search') }}",
          type: "GET",
          data: { query: query },
          success: function (data) {
            let html = '';
            if (data && data.length > 0) {
              html += '<ul class="list-group">';
              $.each(data, function (index, product) {
                html += `
                  <li class="list-group-item search-item" data-url="/product/${product.slug}">
                    <img src="{{ url('userassets/image/product') }}/${product.image}" 
                         alt="${product.name}" width="40" height="40" style="object-fit:cover; margin-right:8px;">
                    ${product.name}
                  </li>`;
              });
              html += '</ul>';
            } else {
              html = '<p class="text-muted p-2">No products found.</p>';
            }
            $('#searchResults').html(html).show();
          },
       error: function (xhr, status) {
    if (status !== "abort") {
        $('#searchResults').hide();
    }
}
        });
      }, 300);
    });
  }

  $(document).on('click', '.search-item', function () {
    const url = $(this).data('url');
    if (url) {
      window.location.href = url;
    }
  });
});



/* ============================================================
   PRODUCT DETAILS PAGE JAVASCRIPT
   ------------------------------------------------------------
   Handles:
   → Description accordion toggle
   → Product image thumbnails
   → Zoom lens functionality
   → Image popup gallery
   → Quantity update & price calculation
   → Color & size selection
   → Thumbnail scroll arrows
=============================================================== */
document.addEventListener("DOMContentLoaded", function() {
  // Check if we're on product details page
  const mainImg = document.getElementById('mainImg');
  if (!mainImg) return; // Exit if not on product page

  /* -------------------------------
     1. Description Accordion Toggle
  --------------------------------*/
  const descSections = document.querySelectorAll(".desc h3");
  descSections.forEach(header => {
    header.addEventListener("click", function() {
      const parent = header.parentElement;
      const symbol = header.querySelector("span");

      // Close all other sections
      document.querySelectorAll(".desc").forEach(sec => {
        if (sec !== parent) {
          sec.classList.remove("active");
          const otherSymbol = sec.querySelector("span");
          if (otherSymbol) otherSymbol.textContent = "+";
        }
      });

      // Toggle current section
      parent.classList.toggle("active");
      if (symbol) {
        symbol.textContent = parent.classList.contains("active") ? "−" : "+";
      }
    });
  });

  /* -------------------------------
     2. Thumbnail Image Click
  --------------------------------*/
  const zoomResult = document.getElementById('zoomResult');
  const thumbs = document.querySelectorAll('#thumbs img');
  const popupClose = document.getElementById('popupClose');
  const popup = document.getElementById('popup');
  const popupMain = document.getElementById('popupMain');
  const popupThumbs = document.getElementById('popupThumbs');
  const popupMainWrapper = document.getElementById('popupMainWrapper');
  
  // Collect all image sources
  const images = [...thumbs].map(t => t.src);

  thumbs.forEach(t => {
    t.addEventListener('click', () => {
      thumbs.forEach(img => img.classList.remove('active'));
      t.classList.add('active');
      mainImg.src = t.src;
      mainImg.dataset.zoom = t.dataset.zoom || t.src;
    });
  });

  /* -------------------------------
     3. Zoom Lens - ONLY FOR DESKTOP (1025px+)
  --------------------------------*/
  const lens = document.getElementById("lens");
  
  // Lens initialization function
  function initLens() {
    if (!lens || !zoomResult) return;
    
    if (window.innerWidth > 1024) {
      // Desktop: enable lens
      lens.style.display = "block";
      zoomResult.style.display = "block";
      
      let lensSize = 120;
      lens.style.width = lensSize + "px";
      lens.style.height = lensSize + "px";
      
      // Remove existing event listeners to prevent duplicates
      mainImg.removeEventListener("mouseenter", showLens);
      mainImg.removeEventListener("mouseleave", hideLens);
      mainImg.removeEventListener("wheel", handleWheel);
      lens.removeEventListener("mousemove", moveLens);
      mainImg.removeEventListener("mousemove", moveLens);
      
      // Add new event listeners
      mainImg.addEventListener("mouseenter", showLens);
      mainImg.addEventListener("mouseleave", hideLens);
      mainImg.addEventListener("wheel", handleWheel, { passive: false });
      
      function showLens() {
        lens.style.display = "block";
        zoomResult.style.display = "block";
      }
      
      function hideLens() {
        lens.style.display = "none";
        zoomResult.style.display = 'none';
      }

      function handleWheel(e) {
        e.preventDefault();
        const rect = mainImg.getBoundingClientRect();
        let oldSize = lensSize;
        const maxLensSize = Math.min(rect.width, rect.height);
        
        if (e.deltaY < 0) {
          lensSize = Math.min(maxLensSize, lensSize + 15);
        } else {
          lensSize = Math.max(80, lensSize - 15);
        }
        
        lens.style.width = lensSize + "px";
        lens.style.height = lensSize + "px";
        
        const diff = (lensSize - oldSize) / 2;
        const currentLeft = parseFloat(lens.style.left) || 0;
        const currentTop = parseFloat(lens.style.top) || 0;
        
        lens.style.left = (currentLeft - diff) + "px";
        lens.style.top = (currentTop - diff) + "px";
      }

      function moveLens(e) {
        const rect = mainImg.getBoundingClientRect();
        let x = e.clientX - rect.left - lensSize / 2;
        let y = e.clientY - rect.top - lensSize / 2;
        
        x = Math.max(0, Math.min(x, rect.width - lensSize));
        y = Math.max(0, Math.min(y, rect.height - lensSize));
        
        lens.style.left = x + "px";
        lens.style.top = y + "px";
        
        const scale = rect.width / lensSize;
        const zoomImg = mainImg.dataset.zoom || mainImg.src;
        zoomResult.style.backgroundImage = `url(${zoomImg})`;
        zoomResult.style.backgroundSize = `${rect.width * scale}px ${rect.height * scale}px`;
        zoomResult.style.backgroundPosition = `-${x * scale}px -${y * scale}px`;
        zoomResult.style.display = 'block';
        zoomResult.style.width = rect.width + 'px';
        zoomResult.style.height = rect.height + 'px';
      }

      lens.addEventListener("mousemove", moveLens);
      mainImg.addEventListener('mousemove', moveLens);
      
    } else {
      // Mobile/Tablet: disable lens
      lens.style.display = "none";
      zoomResult.style.display = "none";
      
      // Remove all event listeners
      mainImg.removeEventListener("mouseenter", showLens);
      mainImg.removeEventListener("mouseleave", hideLens);
      mainImg.removeEventListener("wheel", handleWheel);
      lens.removeEventListener("mousemove", moveLens);
      mainImg.removeEventListener("mousemove", moveLens);
    }
  }

  // Initialize lens on load
  initLens();
  
  // Reinitialize on resize
  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(initLens, 250);
  });

  /* -------------------------------
     4. Image Popup Gallery
  --------------------------------*/
  mainImg.addEventListener('click', () => {
    if (window.innerWidth <= 1024) {
      openPopup();
    }
  });



  // Mobile zoom variables
  let currentScale = 1;
  let startX = 0, startY = 0;
  let offsetX = 0, offsetY = 0;
  let startDistance = 0;
  let isDragging = false;
  let isPinching = false;
  let initialScale = 1;
  

  function openPopup() {
    if (!popup || !popupMain) return;

    popup.classList.add('active');
    popupMain.src = mainImg.src;
    popupMain.addEventListener('click', e => e.stopPropagation());
    popupMain.style.transform = "translate(0,0) scale(1)";

    // Reset zoom state
    currentScale = 1;
    offsetX = 0;
    offsetY = 0;
    isDragging = false;
    isPinching = false;

    // Update thumbnails
    if (popupThumbs) {
      popupThumbs.innerHTML = '';
      images.forEach(src => {
        const img = document.createElement('img');
        img.src = src;
        if (src === mainImg.src) img.classList.add('active');
        img.addEventListener('click', (e) => {
          e.stopPropagation();
          popupMain.src = src;
          popupMain.style.transform = "translate(0,0) scale(1)";
          document.querySelectorAll('#popupThumbs img').forEach(i => i.classList.remove('active'));
          img.classList.add('active');
          
          // Reset zoom state
          currentScale = 1;
          offsetX = 0;
          offsetY = 0;
          isDragging = false;
          isPinching = false;
        });
        popupThumbs.appendChild(img);
      });
    }
    
    setupMobileTouchEvents(); 
  }

function setupMobileTouchEvents() {
  if (!popupMain) return;

  popupMain.addEventListener('touchstart', handleTouchStart, { passive: false });
  popupMain.addEventListener('touchmove', handleTouchMove, { passive: false });
  popupMain.addEventListener('touchend', handleTouchEnd);

  popupMain.style.touchAction = 'none';
}



  function handleTouchStart(e) {
    if (e.touches.length === 2) {
      isPinching = true;
      isDragging = false;
      const touch1 = e.touches[0];
      const touch2 = e.touches[1];
      startDistance = Math.hypot(
        touch2.clientX - touch1.clientX,
        touch2.clientY - touch1.clientY
      );
    } else if (e.touches.length === 1 && currentScale > 1) {
      isDragging = true;
      isPinching = false;
      startX = e.touches[0].clientX - offsetX;
      startY = e.touches[0].clientY - offsetY;
    }
    e.preventDefault();
    initialScale = currentScale;
  }

 function handleTouchMove(e) {
  if (!isPinching && !isDragging) return;
  e.preventDefault();

  /* --------------------
     PINCH ZOOM (CENTERED)
  ---------------------*/
  if (e.touches.length === 2 && isPinching) {
    const touch1 = e.touches[0];
    const touch2 = e.touches[1];

    const currentDistance = Math.hypot(
      touch2.clientX - touch1.clientX,
      touch2.clientY - touch1.clientY
    );

    if (startDistance > 0) {
      let newScale = initialScale * (currentDistance / startDistance);

      // Zoom limits
      newScale = Math.max(1, Math.min(newScale, 3));

      currentScale = newScale;

      // 🔒 CENTER ZOOM (NO OFFSET CHANGE)
      offsetX = 0;
      offsetY = offsetY;

      applyBoundaries();
      updateTransform();
    }
  }

  /* --------------------
     DRAG – ONLY UP & DOWN
  ---------------------*/
  else if (e.touches.length === 1 && isDragging && currentScale > 1) {
    const touch = e.touches[0];

    // ❌ LEFT–RIGHT DISABLED
    offsetX = 0;

    // ✅ ONLY UP–DOWN
    offsetY = touch.clientY - startY;

    applyBoundaries();
    updateTransform();
  }
}


  function handleTouchEnd(e) {
    if (e.touches.length < 2) {
      isPinching = false;
      startDistance = 0;
    }
    if (e.touches.length === 0) {
      isDragging = false;
      applyBoundaries(true);
    }
    
   if (currentScale <= 1.1) {
      popupMain.style.transition = 'transform 0.25s ease';
      currentScale = 1;
      offsetX = 0;
      offsetY = 0;
      updateTransform();
      setTimeout(() => popupMain.style.transition = '', 250);
    }
  }

  function applyBoundaries(smooth = false) {
       if (!popupMainWrapper) return;
    if (currentScale <= 1) {
      offsetX = 0;
      offsetY = 0;
      return;
    }
    
    const rect = popupMain.getBoundingClientRect();
    const wrapperRect = popupMainWrapper.getBoundingClientRect();
    
    const scaledWidth = rect.width * currentScale;
    const scaledHeight = rect.height * currentScale;
    
    const maxOffsetX = Math.max(0, (scaledWidth - wrapperRect.width) / 2);
    const maxOffsetY = Math.max(0, (scaledHeight - wrapperRect.height) / 2);
    
    offsetX = Math.max(-maxOffsetX, Math.min(offsetX, maxOffsetX));
    offsetY = Math.max(-maxOffsetY, Math.min(offsetY, maxOffsetY));
    
    if (smooth) {
      popupMain.style.transition = 'transform 0.2s ease-out';
      setTimeout(() => {
        popupMain.style.transition = '';
      }, 200);
    }
  }

  function updateTransform() {
    popupMain.style.transform = `translate(${offsetX}px, ${offsetY}px) scale(${currentScale})`;
  }

 function closePopup() {
  if (!popup) return;

  popup.classList.remove('active');
  popupMain.style.transform = "translate(0,0) scale(1)";

  currentScale = 1;
  offsetX = 0;
  offsetY = 0;
  isDragging = false;
  isPinching = false;
}


  if (popupClose) {
    popupClose.addEventListener('click', closePopup);
  }

  if (popup) {
    popup.addEventListener('click', e => {
      if (e.target === popup || e.target === popupClose) {
        closePopup();
      }
    });
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && popup && popup.classList.contains('active')) {
      closePopup();
    }
  });

  /* -------------------------------
     5. Quantity & Price Calculation
  --------------------------------*/
  // window.changeQty = function(amount) {
  //   const qtyInput = document.getElementById('qtyInput');
  //   const formQty = document.getElementById('formQty');
  //   const displayPrice = document.getElementById('displayPrice');
  //   const basePrice = parseFloat(document.getElementById('basePrice')?.value || 0);
    
  //   if (!qtyInput) return;
    
  //   let qty = parseInt(qtyInput.value) + amount;
  //   if (isNaN(qty) || qty < 1) qty = 1;
    
  //   qtyInput.value = qty;
    
  //   if (formQty) formQty.value = qty;
  //   if (displayPrice && !isNaN(basePrice)) {
  //     const newPrice = basePrice * qty;
  //     displayPrice.textContent = '₹' + newPrice.toFixed(2);
  //   }
    
  //   const formPrice = document.getElementById('formPrice');
  //   if (formPrice && !isNaN(basePrice)) {
  //     formPrice.value = (basePrice * qty).toFixed(2);
  //   }
  // };
   window.changeQty = function(amount) {
    const qtyInput = document.getElementById('qtyInput');
    const formQty = document.getElementById('formQty');
    const displayPrice = document.getElementById('displayPrice');
    const basePrice = parseFloat(document.getElementById('basePrice')?.value || 0);
    
    if (!qtyInput) return;
    
    let newQty = parseInt(qtyInput.value) + amount;
    if (isNaN(newQty) || newQty < 1) newQty = 1;
    
    // Check against available stock
    if (newQty > productStock) {
        alert('Only ' + productStock + ' item(s) available in stock.');
        return; // Don't update the quantity
    }
    
    qtyInput.value = newQty;
    
    if (formQty) formQty.value = newQty;
    if (displayPrice && !isNaN(basePrice)) {
        const newPrice = basePrice * newQty;
        displayPrice.textContent = '₹' + newPrice.toFixed(2);
    }
    
    const formPrice = document.getElementById('formPrice');
    if (formPrice && !isNaN(basePrice)) {
        formPrice.value = (basePrice * newQty).toFixed(2);
    }
};

  /* -------------------------------
     6. Color Selection
  --------------------------------*/
  window.selectColor = function(imageSrc, element) {
    document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('active'));
    element.classList.add('active');
    mainImg.src = imageSrc;
    
    const selectedColorInput = document.getElementById('selectedColor');
    if (selectedColorInput) {
      selectedColorInput.value = imageSrc;
    }
  };

  /* -------------------------------
     7. Thumbnail Scrolling
  --------------------------------*/
  const thumbBox = document.getElementById("thumbs");
  const upBtn = document.querySelector(".thumb-up");
  const downBtn = document.querySelector(".thumb-down");
  
  if (thumbBox && upBtn && downBtn) {
    const totalThumbs = thumbBox.querySelectorAll("img").length;
    
    function isMobile() {
      return window.innerWidth <= 768;
    }

    if (totalThumbs > 5) {
      function updateArrows() {
        if (isMobile()) {
          const maxScroll = thumbBox.scrollWidth - thumbBox.clientWidth;
          upBtn.classList.toggle("show", thumbBox.scrollLeft > 0);
          downBtn.classList.toggle("show", thumbBox.scrollLeft < maxScroll);
        } else {
          const maxScroll = thumbBox.scrollHeight - thumbBox.clientHeight;
          upBtn.classList.toggle("show", thumbBox.scrollTop > 0);
          downBtn.classList.toggle("show", thumbBox.scrollTop < maxScroll);
        }
      }

      upBtn.addEventListener("click", () => {
        if (isMobile()) {
          thumbBox.scrollLeft -= 120;
        } else {
          thumbBox.scrollTop -= 120;
        }
        setTimeout(updateArrows, 150);
      });

      downBtn.addEventListener("click", () => {
        if (isMobile()) {
          thumbBox.scrollLeft += 120;
        } else {
          thumbBox.scrollTop += 120;
        }
        setTimeout(updateArrows, 150);
      });

      thumbBox.addEventListener("scroll", updateArrows);
      updateArrows();
    }
  }
});

/* ============================================================
   SIGNUP & LOGIN VALIDATION
=============================================================== */
document.addEventListener("DOMContentLoaded", function() {
  // Forgot Password Link
  const forgotLink = document.getElementById("forgotPasswordLink");
  if (forgotLink) {
    forgotLink.addEventListener("click", function(e) {
      e.preventDefault();
      const loginModal = document.getElementById("loginModalTop");
      const forgotModal = document.getElementById("forgotPasswordModal");
      if (loginModal) loginModal.style.display = "none";
      if (forgotModal) forgotModal.style.display = "block";
    });
  }

  // --- Signup validation (phone must be exactly 10 digits) ---
  const signupForm = document.getElementById("signupForm");
  const phoneInput = document.getElementById("signupPhone");
  const phoneError = document.getElementById("phoneError");
  const signupError = document.getElementById("signupError");

  function validatePhone(phone) {
    const digits = phone.replace(/\D/g, "");
    return /^\d{10}$/.test(digits);
  }

  // Live feedback while typing
  if (phoneInput) {
    phoneInput.addEventListener("input", function() {
      const val = this.value.replace(/\D/g, "");
      this.value = val;
      
      if (val.length === 10 && phoneError) {
        phoneError.style.display = "none";
        phoneError.textContent = "";
      }
    });
  }

  if (signupForm) {
    signupForm.addEventListener("submit", function(e) {
      e.preventDefault();
      
      if (phoneError) {
        phoneError.style.display = "none";
        phoneError.textContent = "";
      }
      if (signupError) {
        signupError.style.display = "none";
        signupError.textContent = "";
      }

      const phoneVal = phoneInput ? phoneInput.value : "";
      const nameVal = document.getElementById("signupName")?.value.trim() || "";
      const emailVal = document.getElementById("signupEmail")?.value.trim() || "";
      const passVal = document.getElementById("signupPassword")?.value || "";
      const confVal = document.getElementById("signupConfirmPassword")?.value || "";

      // Phone validation
      if (!validatePhone(phoneVal)) {
        if (phoneError) {
          phoneError.style.display = "block";
          phoneError.textContent = "Please enter a valid 10-digit phone number (numbers only).";
        }
        if (phoneInput) phoneInput.focus();
        return;
      }

      // Name validation
      if (nameVal.length < 2) {
        if (signupError) {
          signupError.style.display = "block";
          signupError.textContent = "Please enter a valid name.";
        }
        return;
      }

      // Email validation (basic)
      if (emailVal && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
        if (signupError) {
          signupError.style.display = "block";
          signupError.textContent = "Please enter a valid email address.";
        }
        return;
      }

      // Password validation
      if (passVal.length < 6) {
        if (signupError) {
          signupError.style.display = "block";
          signupError.textContent = "Password should be at least 6 characters.";
        }
        return;
      }
      
      if (passVal !== confVal) {
        if (signupError) {
          signupError.style.display = "block";
          signupError.textContent = "Password and Confirm Password do not match.";
        }
        return;
      }

      // If all validations pass, submit the form
      console.log("Signup data validated:", { name: nameVal, email: emailVal, phone: phoneVal });
      
      
    });
  }
});

/* ============================================================
   REVIEW SYSTEM
=============================================================== */
document.addEventListener("DOMContentLoaded", function() {
  const reviewsList = document.getElementById("reviewsList");
  const openReviewBtn = document.getElementById("openReviewBtn");
  if (!reviewsList && !openReviewBtn) return;
  
  function renderReviews(list) {
    if (!reviewsList) return;
    
    reviewsList.innerHTML = "";

    list.forEach(r => {
      const stars = "★★★★★".slice(0, r.rating) + "☆☆☆☆☆".slice(0, 5 - r.rating);
      const images = r.images.map(img => `<img src="${img}" alt="Review Image" class="review-img">`).join("");

      reviewsList.innerHTML += `
        <div class="review">
          <div class="review-header mb-2">
            <div class="stars me-2">${stars}</div>
            <div class="fw-bold me-2">${r.name}</div>
            <span class="verified me-2">Verified</span>
            <div class="date ms-auto">${r.date}</div>
          </div>
          <div class="review-headline"><strong>${r.headline}</strong></div>
          <div class="review-text">${r.text}</div>
          <div class="review-images">${images}</div>
        </div>
      `;
    });
  }


  renderReviews(reviews);
  const sortSelect = document.getElementById("sortSelect");
  if (sortSelect) {
    sortSelect.addEventListener("change", function () {
      let sorted = [...reviews];

      if (this.value === "high") {
        sorted.sort((a, b) => b.rating - a.rating);
      } else if (this.value === "low") {
        sorted.sort((a, b) => a.rating - b.rating);
      } else {
        sorted.sort((a, b) => new Date(b.date) - new Date(a.date));
      }

      renderReviews(sorted);
    });
  }

  if (openReviewBtn) {
    openReviewBtn.addEventListener("click", () => {
      const reviewOverlay = document.getElementById("reviewOverlay");
      if (reviewOverlay) reviewOverlay.style.display = "flex";
    });
  }

  const closePopup = document.getElementById("closePopup");
  if (closePopup) {
    closePopup.addEventListener("click", () => {
      const reviewOverlay = document.getElementById("reviewOverlay");
      if (reviewOverlay) reviewOverlay.style.display = "none";
    });
  }


  const stars = document.querySelectorAll(".star");
  stars.forEach(star => {
    star.addEventListener("click", function () {
      const rating = this.dataset.value;
      const ratingInput = document.getElementById("rating");
      if (ratingInput) ratingInput.value = rating;

      document.querySelectorAll(".star").forEach(s => {
        s.classList.toggle("selected", s.dataset.value <= rating);
      });
    });
  });


  const reviewImagesInput = document.getElementById("reviewImages");
  if (reviewImagesInput) {
    reviewImagesInput.addEventListener("change", function () {
      const files = this.files;
      const previewContainer = document.getElementById("imagePreview");
      if (!previewContainer) return;
      
      previewContainer.innerHTML = "";

      Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function (e) {
          const img = document.createElement("img");
          img.src = e.target.result;
          img.className = "preview-img";
          previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
      });
    });
  }

  const reviewForm = document.getElementById("reviewFormCustom");
  if (reviewForm) {
    reviewForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const name = document.getElementById("name")?.value || "";
      const rating = parseInt(document.getElementById("rating")?.value || 0);
      const text = document.getElementById("reviewText")?.value || "";
      const images = Array.from(document.getElementById("reviewImages")?.files || []).map(file => URL.createObjectURL(file));

      if (!rating) {
        alert("Please select a rating!");
        return;
      }

      if (!name.trim()) {
        alert("Please enter your name!");
        return;
      }

      if (!text.trim()) {
        alert("Please enter your review!");
        return;
      }

      const today = new Date();
      const date = `${today.getMonth() + 1}/${today.getDate()}/${today.getFullYear()}`;

      reviews.unshift({name, rating, headline: "", text, date, images});
      renderReviews(reviews);

      this.reset();
      document.querySelectorAll(".star").forEach(s => s.classList.remove("selected"));
      
      const imagePreview = document.getElementById("imagePreview");
      if (imagePreview) imagePreview.innerHTML = "";
      
      const reviewOverlay = document.getElementById("reviewOverlay");
      if (reviewOverlay) reviewOverlay.style.display = "none";
      
      alert("Thank you for your review!");
    });
  }
});
