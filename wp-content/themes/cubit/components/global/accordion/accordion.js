document.addEventListener('alpine:init', () => {
  Alpine.data('Accordion', ({ index = 0, allCollapsed = true }) => ({
    index,
    allCollapsed,
  }))
})
