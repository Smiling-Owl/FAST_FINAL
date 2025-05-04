// admin_login.js
document.addEventListener('DOMContentLoaded', function() {
    const carouselImages = document.querySelectorAll('.carousel-slide');
    let currentIndex = 0;

    function showImage(index) {
        carouselImages.forEach(img => img.classList.remove('active'));
        carouselImages[index].classList.add('active');
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % carouselImages.length;
        showImage(currentIndex);
    }

    // Show the first image initially
    if (carouselImages.length > 0) {
        showImage(currentIndex);
        // Set interval for automatic sliding (e.g., every 3 seconds)
        setInterval(nextImage, 3000);
    }
});