<!-- popUp.php - Modern Welcome Modal with Dynamic Carousel -->
<div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-xl rounded-4 overflow-hidden">

            <!-- Close button (top-right) -->
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 mt-3 me-3 z-3" 
                    data-bs-dismiss="modal" aria-label="Close" style="font-size: 1.5rem;"></button>

            <!-- Carousel -->
            <div class="carousel slide" id="welcomeCarousel" data-bs-ride="carousel">
                <div class="carousel-inner" id="carouselInner"></div>

                <!-- Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#welcomeCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#welcomeCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>

                <!-- Dynamic Indicators -->
                <div class="carousel-indicators" id="carouselIndicators"></div>
            </div>

            <!-- Footer CTA -->
            <div class="modal-footer border-0 justify-content-center py-4 bg-gradient-light">
                <button type="button" class="btn btn-success btn-lg px-5 py-3 rounded-pill shadow" data-bs-dismiss="modal">
                    Continue →
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Trigger on first login -->
<script>
<?php
if (isset($_SESSION['just_logged_in']) && $_SESSION['just_logged_in'] === true) {
    unset($_SESSION['just_logged_in']);
?>
document.addEventListener('DOMContentLoaded', () => {
    const modalEl = document.getElementById('welcomeModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl, {
            backdrop: 'static',   // prevents closing by clicking outside
            keyboard: true
        });
        modal.show();
    }
});
<?php } ?>

// Carousel data from localStorage (shared with admin)
let slides = JSON.parse(localStorage.getItem('carouselSlides')) || [];

// Render carousel + indicators
function renderWelcomeCarousel() {
    const inner = document.getElementById('carouselInner');
    const indicators = document.getElementById('carouselIndicators');
    
    inner.innerHTML = '';
    indicators.innerHTML = '';

    if (slides.length === 0) {
        inner.innerHTML = `
            <div class="carousel-item active text-center py-5">
                <div class="py-5 my-5">
                    <h3>No welcome slides available</h3>
                    <p>Slides will appear here after being added in admin.</p>
                </div>
            </div>`;
        return;
    }

    slides.forEach((slide, index) => {
        const isActive = index === 0 ? 'active' : '';

        // Slide item
        const item = document.createElement('div');
        item.className = `carousel-item ${isActive} position-relative`;
        item.innerHTML = `
            <img src="${slide.imgUrl}" 
                 class="d-block w-100" 
                 style="max-height: 75vh; object-fit: cover;" 
                 alt="${slide.title || 'Slide'}">
            <div class="carousel-caption d-flex flex-column justify-content-center h-100 text-white text-start ps-5 pb-5">
                <div class="caption-bg p-4 rounded-4" style="background: rgba(0,0,0,0.45); max-width: 650px;">
                    <h2 class="display-4 fw-bold mb-3 text-shadow">${slide.title}</h2>
                    <p class="fs-4 fw-light mb-4">${slide.desc}</p>
                    ${slide.btnText && slide.btnLink ? `
                        <a href="${slide.btnLink}" 
                           class="btn btn-success btn-lg px-5 py-3 rounded-pill shadow" 
                           target="_blank">
                            ${slide.btnText}
                        </a>
                    ` : ''}
                </div>
            </div>
        `;
        inner.appendChild(item);

        // Indicator
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = index === 0 ? 'active' : '';
        btn.setAttribute('data-bs-target', '#welcomeCarousel');
        btn.setAttribute('data-bs-slide-to', index);
        btn.setAttribute('aria-label', `Slide ${index + 1}`);
        if (index === 0) btn.setAttribute('aria-current', 'true');
        indicators.appendChild(btn);
    });
}

// Run render
renderWelcomeCarousel();
</script>

<style>
/* Modern enhancements */
.text-shadow {
    text-shadow: 0 4px 16px rgba(0,0,0,0.7);
}
.carousel-caption {
    bottom: 0;
    top: 0;
    padding: 1.5rem;
}
.caption-bg {
    backdrop-filter: blur(4px);
}
.carousel-indicators {
    bottom: 1.5rem !important;
}
.carousel-indicators button {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background-color: rgba(255,255,255,0.6);
    border: 2px solid white;
    margin: 0 10px;
    opacity: 0.7;
    transition: all 0.3s;
}
.carousel-indicators .active {
    opacity: 1;
    background-color: #198754;
    border-color: white;
    transform: scale(1.2);
}
.bg-gradient-light {
    background: linear-gradient(180deg, rgba(255,255,255,0.98) 0%, white 100%);
}
.shadow-xl {
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important;
}
.rounded-4 {
    border-radius: 1.5rem !important;
}
</style>