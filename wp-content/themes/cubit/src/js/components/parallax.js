export default () => {
  // apply parallax effect to any element with a data-speed attribute
  const parents = document.querySelectorAll('[data-parent]')
  parents.forEach(parent => {
    const scaleElements = parent.querySelectorAll('[data-scale]')
    const translateElements = parent.querySelectorAll('[data-translate]')

    let additionalScaleRequired = 0
    const parentHeight = parent.clientHeight
    if (parentHeight < window.innerHeight) {
      const spaceHeight = window.innerHeight - parentHeight
      const scrollProgress =
        (parentHeight + spaceHeight) / (2 * parentHeight + spaceHeight)
      additionalScaleRequired = (scrollProgress * 2 - 1) * 2
    }

    if (scaleElements.length > 0 || translateElements.length > 0) {
      const tl = gsap.timeline()
      scaleElements.forEach(el => {
        tl.to(
          el,
          {
            scale: parseFloat(el.getAttribute('data-scale')),
          },
          0
        )
      })
      translateElements.forEach(el => {
        const value = parseFloat(el.getAttribute('data-translate'))
        let scaling = 1
        if (el.hasAttribute('data-cover-parent')) {
          scaling += additionalScaleRequired
        }

        tl.fromTo(
          el,
          {
            scale: scaling,
            yPercent: (value - 1) * -100,
          },
          {
            scale: scaling,
            yPercent: (value - 1) * 100,
          },
          0
        )
      })
      const start = parent.dataset.start
      const end = parent.dataset.end
      ScrollTrigger.create({
        trigger: parent,
        start: start ?? 'top bottom',
        end: end ?? 'bottom top',
        animation: tl,
        scrub: true,
      })
    }
  })
}
