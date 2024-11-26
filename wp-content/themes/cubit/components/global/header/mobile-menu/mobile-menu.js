document.addEventListener('alpine:init', () => {
  Alpine.data('MobileMenu', () => ({
    open: false,
    index: 0,
    init() {
      this.$watch('open', open => {
        if (!open) {
          this.depth = 0
          this.index0 = 0
        }
      })
      window.addEventListener('close-mobile-menu', () => {
        setTimeout(() => {
          this.open = false
        }, 300)
      })
      this.$el.querySelectorAll('.scroller').forEach(el => {
        el.addEventListener('scroll', e => {
          const scroller = e.target
          const scrollTopEnd = scroller.scrollHeight - scroller.clientHeight
          if (scroller.scrollTop < scrollTopEnd - 10) {
            this.more = true
          } else {
            this.more = false
          }
        })
      })
    },
    back() {
     
    },
  }))
})
