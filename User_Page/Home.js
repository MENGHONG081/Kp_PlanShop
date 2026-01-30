
  const container = document.getElementById('productScroll');
  const scrollAmount = 250; // how far to scroll each click

  document.getElementById('scrollLeft').onclick = () => {
    container.scrollLeft -= scrollAmount;
  };

  document.getElementById('scrollRight').onclick = () => {
    container.scrollLeft += scrollAmount;
  };

  const input = document.getElementById('searchInput');
  input.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
      const query = input.value.trim();
      if (query) {
        // Example: redirect to search page
        window.location.href = `search.php?q=${encodeURIComponent(query)}`;
        
        // Or: run AJAX fetch
        // fetch(`/api/search?q=${encodeURIComponent(query)}`)
        //   .then(res => res.json())
        //   .then(data => console.log(data));
      }
    }
  });
const productImgs = document.querySelectorAll('.product-img');
const modal = document.getElementById('productModal');
const modalImg = document.getElementById('modalProductImg');
const modalTitle = document.getElementById('productModalLabel');
productImgs.forEach(img => {
  img.addEventListener('click', function() {
    modalImg.src = this.src;
    modalTitle.textContent = this.getAttribute('data-title') || this.alt;
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
  });
});
    // aboute page is postcard float when cicked bottom
function PostCard() {
    const card = document.getElementById("Postcard");
    card.classList.toggle("hidden");
}
