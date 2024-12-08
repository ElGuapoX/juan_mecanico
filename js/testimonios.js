const testimonials = document.querySelectorAll('.testimonial-item');
const dots = document.querySelectorAll('.dot');
const prevButton = document.querySelector('.prev');
const nextButton = document.querySelector('.next');
let currentIndex = 0;

// Función para mostrar el testimonio actual
function showTestimonial(index) {
  testimonials.forEach((item, i) => {
    item.classList.toggle('active', i === index);
    dots[i].classList.toggle('active', i === index);
  });
}

// Event listeners para los botones
prevButton.addEventListener('click', () => {
  currentIndex = (currentIndex === 0) ? testimonials.length - 1 : currentIndex - 1;
  showTestimonial(currentIndex);
});

nextButton.addEventListener('click', () => {
  currentIndex = (currentIndex === testimonials.length - 1) ? 0 : currentIndex + 1;
  showTestimonial(currentIndex);
});

// Event listeners para los puntos (dots)
dots.forEach((dot, index) => {
  dot.addEventListener('click', () => {
    currentIndex = index;
    showTestimonial(currentIndex);
  });
});

// Inicializa mostrando el primer testimonio
showTestimonial(currentIndex);
