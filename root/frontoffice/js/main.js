// Animation au scroll
function reveal() {
    const reveals = document.querySelectorAll(".reveal");
    
    for (let i = 0; i < reveals.length; i++) {
        const windowHeight = window.innerHeight;
        const elementTop = reveals[i].getBoundingClientRect().top;
        const elementVisible = 150;
        
        if (elementTop < windowHeight - elementVisible) {
            reveals[i].classList.add("active");
        } else {
            reveals[i].classList.remove("active");
        }
    }
}

window.addEventListener("scroll", reveal);
reveal(); // Appel initial

// Initialisation du carousel
const myCarousel = document.querySelector('#mainCarousel');
const carousel = new bootstrap.Carousel(myCarousel, {
    interval: 3000,
    ride: 'carousel'
});
// Mettre en surbrillance l'élément de menu actif
document.querySelectorAll('.nav-link').forEach(link => {
    if(link.href === window.location.href) {
        link.classList.add('active');
        link.setAttribute('aria-current', 'page');
    }
}); 
