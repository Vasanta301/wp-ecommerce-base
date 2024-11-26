document.addEventListener('alpine:init', () => {
  Alpine.data('Scroller', () => ({
    more: true,
    init() {
      this.$refs.scroller.addEventListener('scroll', e => {
        const scroller = e.target
        const scrollTopEnd = scroller.scrollHeight - scroller.clientHeight
        if (scroller.scrollTop < scrollTopEnd - 10) {
          this.more = true
        } else {
          this.more = false
        }
      })
      const observer = new IntersectionObserver((entries, observer) => {
        if (
          this.$refs.scroller.scrollHeight <= this.$refs.scroller.clientHeight
        ) {
          this.more = false
        } else {
          this.more = true
        }
      })
      observer.observe(this.$refs.scroller)
    },
  }))
})
