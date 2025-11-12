document.addEventListener("DOMContentLoaded", () => {
    const testimonialItems = document.querySelectorAll(".testimonial-item");
    const puntos = document.querySelectorAll(".punto");
    const prevBtn = document.querySelector(".prev");
    const nextBtn = document.querySelector(".next");
    let activeIndex = 0;

    // Función para actualizar el slider
    const updateSlider = () => {
        // Actualizar testimonios
        testimonialItems.forEach((item, index) => {
            item.classList.toggle("active", index === activeIndex);
        });

        // Actualizar puntos
        puntos.forEach((punto, index) => {
            punto.classList.toggle("active", index === activeIndex);
        });
    };

    // Mover al siguiente testimonio
    const nextTestimonial = () => {
        activeIndex = (activeIndex + 1) % testimonialItems.length;
        updateSlider();
    };

    // Mover al testimonio anterior
    const prevTestimonial = () => {
        activeIndex = (activeIndex - 1 + testimonialItems.length) % testimonialItems.length;
        updateSlider();
    };

    // Agregar eventos a los botones
    nextBtn.addEventListener("click", nextTestimonial);
    prevBtn.addEventListener("click", prevTestimonial);

    // Agregar eventos a los puntos
    puntos.forEach((punto, index) => {
        punto.addEventListener("click", () => {
            activeIndex = index;
            updateSlider();
        });
    });

    // Inicializar slider
    updateSlider();
});
