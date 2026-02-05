<!-- popUp.php - Modern & Professional Welcome Modal -->
<div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-xl rounded-4 overflow-hidden bg-white">
            <!-- Close button -->
            <div class="modal-header border-0 position-absolute top-0 end-0 z-3 p-4">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

              <!-- Carousel form admin -->
           <!-- <div id="customCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner" id="carouselInner"></div>
            </div> -->


            <!-- Carousel -->
            <div id="promoCarousel" class="carousel slide" data-bs-ride="false">
                <div class="carousel-inner">
                    <!-- Slide 1: Personalized Welcome -->
                    <div class="carousel-item active">
                        <img src="image/600083714_1569104430952801_105105535006148250_n.jpg" 
                             class="d-block w-100" 
                             style="max-height: 70vh; object-fit: cover;" 
                             alt="Welcome to our plant family">
                        <div class="carousel-caption d-flex flex-column justify-content-center h-100 text-white text-start ps-5 pb-5">
                            <h2 class="display-4 fw-bold mb-3 text-shadow">Cambodia Need Peace</h2>
                            <p class="fs-4 fw-light mb-4 opacity-90">Thai Military Attacks and Violations of Cambodian</p>
                            <a href="https://angkor-whispers.vercel.app/" class="btn btn-success btn-lg px-5 py-3 rounded-pill shadow">Need Peace →</a>
                        </div>
                    </div>

                    <!-- Slide 2: Promotion -->
                    <div class="carousel-item">
                        <img src="image/14.jpg" 
                             class="d-block w-100" 
                             style="max-height: 70vh; object-fit: cover;" 
                             alt="20% Off Indoor Plants">
                        <div class="carousel-caption d-none d-md-block text-center">
                            <div class="bg-dark bg-opacity-60 py-4 px-5 rounded-4 d-inline-block">
                                <h3 class="display-5 fw-bold mb-3">20% OFF This Week Only</h3>
                                <p class="fs-4 mb-4">Indoor & low-light plants – use code <strong>GREEN20</strong></p>
                                <button type="button" class="btn btn-outline-light btn-lg px-5 rounded-pill" data-bs-dismiss="modal">
                                    Shop Now
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 3: Another promotion / feature -->
                    <div class="carousel-item">
                        <img src="image/2.jpg" 
                             class="d-block w-100" 
                             style="max-height: 70vh; object-fit: cover;" 
                             alt="New Arrivals">
                        <div class="carousel-caption d-none d-md-block text-center">
                            <div class="bg-success bg-opacity-70 py-4 px-5 rounded-4 d-inline-block">
                                <h3 class="display-5 fw-bold mb-3">New Tropical Arrivals</h3>
                                <p class="fs-4 mb-4">Rare finds & healthy starters – perfect for your space</p>
                                <button type="button" class="btn btn-light btn-lg px-5 rounded-pill" data-bs-dismiss="modal">
                                    Discover Collection
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Add more slides as needed -->
                </div>

                <!-- Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark bg-opacity-50 rounded-circle p-4" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-dark bg-opacity-50 rounded-circle p-4" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>

                <!-- Indicators (optional – clean dots) -->
                <div class="carousel-indicators position-absolute bottom-0 mb-5">
                    <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
            </div>

            <!-- Footer CTA (shown when carousel ends or if needed) -->
            <div class="modal-footer border-0 justify-content-center py-4 bg-gradient-light">
                <button type="button" class="btn btn-outline-success btn-lg px-5 py-3 rounded-pill" data-bs-dismiss="modal">
                    Continue Shopping →
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Trigger Script -->
<script>
<?php
if (isset($_SESSION['just_logged_in']) && $_SESSION['just_logged_in'] === true) {
    unset($_SESSION['just_logged_in']);
?>
document.addEventListener('DOMContentLoaded', () => {
    const modalEl = document.getElementById('welcomeModal');
    if (modalEl) {
        const welcomeModal = new bootstrap.Modal(modalEl, {
            backdrop: 'static',
            keyboard: true  // allow ESC now – feels more modern
        });
        welcomeModal.show();
    }
});
<?php } ?>

const slides = JSON.parse(localStorage.getItem("slides") || "[]");
const carouselInner = document.getElementById("carouselInner");

slides.forEach((slide, index) => {
  const item = document.createElement("div");
  item.className = "carousel-item" + (index === 0 ? " active" : "");
  item.innerHTML = `
    <img src="${slide.image}" class="d-block w-100" style="max-height:70vh; object-fit:cover;" alt="${slide.title}">
    <div class="carousel-caption d-flex flex-column justify-content-center h-100 text-white text-start ps-5 pb-5">
      <h2 class="display-4 fw-bold mb-3 text-shadow">${slide.title}</h2>
      <p class="fs-4 fw-light mb-4 opacity-90">${slide.description}</p>
    </div>
  `;
  carouselInner.appendChild(item);
});

</script>

<style>
/* Quick modern enhancements – move to your CSS file later */
.text-shadow {
    text-shadow: 0 4px 12px rgba(0,0,0,0.6);
}
.carousel-caption h2, .carousel-caption h3 {
    letter-spacing: -0.5px;
}
.carousel-control-prev-icon, .carousel-control-next-icon {
    background-size: 60%;
}
.carousel-indicators [data-bs-target] {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: rgba(255,255,255,0.5);
    border: 2px solid white;
    margin: 0 8px;
    opacity: 0.7;
}
.carousel-indicators .active {
    opacity: 1;
    background-color: white;
    border-color: #198754;
}
.bg-gradient-light {
    background: linear-gradient(to bottom, rgba(255,255,255,0.95), white);
}
.rounded-4 { border-radius: 1.5rem !important; }
.shadow-xl { box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important; }
</style>