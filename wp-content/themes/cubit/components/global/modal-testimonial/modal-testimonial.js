document.addEventListener('alpine:init', () => {
  Alpine.data('modalTestimonial', () => ({
    open: false,
    testimonial: null
  }))
})