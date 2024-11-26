document.addEventListener('alpine:init', () => {
  Alpine.data('DesktopMenu', () => ({
    open: false,
    index0: 0,
    index1: 0,
    index2: 0,
    lastHovered: {
      menuIndex: 1,
      itemIndex: 0,
    },
    selectedMenuType: 'treatment_application',
    init() {
      this.$watch('open', open => {
        if (open) {
          setTimeout(() => {
            document.body.classList.add('overflow-hidden')
          }, 10)
        } else {
          document.body.classList.remove('overflow-hidden')
        }
      })
    },
    select(menuIndex, itemIndex) {
      if (menuIndex == 0) {
        this.index0 = itemIndex
        this.index1 = 0
        this.index2 = 0
      } else if (menuIndex == 1) {
        this.index1 = itemIndex
        this.index2 = 0
      } else if (menuIndex == 2) {
        this.index2 = itemIndex
      }
      this.lastHovered.menuIndex = menuIndex
      this.lastHovered.itemIndex = itemIndex
    },
  }))
})
