document.addEventListener("alpine:init", () => {
  Alpine.data("metricComponent", (target, time) => ({
    current: 0,
    target: target,
    time: time,
    visible: false, 
    observer: null,

    init() {
      this.observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting && !this.visible) {
            this.visible = true;
            this.startAnimation(); 
          }
        });
      });

      this.observer.observe(this.$el);
    },

    startAnimation() {
      const start = this.current;
      const interval = Math.max(this.time / (this.target - start), 5);
      const step = (this.target - start) / (this.time / interval);

      const handle = setInterval(() => {
        if (this.current < this.target) {
          this.current += step;
        } else {
          clearInterval(handle);
          this.current = this.target;
        }
      }, interval);
    },
  }));
});
