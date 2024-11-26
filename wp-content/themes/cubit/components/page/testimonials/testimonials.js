document.addEventListener("alpine:init", () => {
  Alpine.data("Testimonial", () => ({
    hoverIndex: -1,
    init() {
      const swiper = new Swiper(this.$refs.swiperContainer, {
        loop: true,
        slidesPerView: 1,
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
        },
        breakpoints: {
          360: {
            slidesPerView: 1,
            spaceBetween: 10,
          },
          768: {
            slidesPerView: 2,
            spaceBetween: 20,
          },
          1024: {
            slidesPerView: 3,
            spaceBetween: 30,
          },
        },
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
        on: {
          autoplayTimeLeft(s, time, progress) { },
        },
      });

      swiper.on("realIndexChange", (swiper) => {
        this.index = swiper.realIndex;
      });

      this.$watch("hoverIndex", (value) => {
        //console.log(value);
      });
    },
  }));
});
