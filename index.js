// Hide the nav bar when scrolling (source: Dcode)
{
    const nav = document.querySelector(".navigation-bar");
    let lastScrollY = window.scrollY;

    window.addEventListener("scroll", () => {
        if (lastScrollY < window.scrollY){
            console.log("we are going down");
            nav.classList.add("nav--hidden");
        }
        else {
            console.log("we are going up");
            nav.classList.remove("nav--hidden");

        }

        lastScrollY = window.scrollY;
    });
}

// JavaScript to make the carousel work
const images = document.querySelectorAll('.carousel-slide');  // Get all images in the carousel
let currentIndex = 0; // Index of the current active image

// Function to show the next image
function showNextImage() {
    // Hide current image
    images[currentIndex].classList.remove('active');
    
    // Update the index (circular navigation)
    currentIndex = (currentIndex + 1) % images.length;

    // Show the next image
    images[currentIndex].classList.add('active');
}

// Initially show the first image
images[currentIndex].classList.add('active');

// Change the image every 3 seconds
setInterval(showNextImage, 5000);
