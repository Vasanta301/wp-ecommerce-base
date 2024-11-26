export default elementId => {
  // --- Parameters ---
  const navbar = document.getElementById('header-container') // Must have position: fixed
  const navbarHeader = document.getElementById('header')
  const threshold = 2 * navbarHeader.clientHeight // Number of px of space at the top of the page where the navbar won't disappear


  // How many pixels you have to scroll before navbar transitions
  const scrollDownThreshold = 25
  const scrollUpThreshold = 100
  // --- End Parameters ---

  const placeholder = document.getElementById('header-placeholder')

  // Give the DOM some time to render
  navbar.style.position = 'fixed'

  let trigger

  navbar.dataset.initialClass = navbar.getAttribute('class')
  const transition = window
    .getComputedStyle(navbar) 
    .getPropertyValue('transition')
  navbar.style.transition = [transition, '0.3s transform ease-in-out'].join(',')
  let previousScrollTop = 0
  let cumulativeAmountScrolledDown = 0
  let cumulativeAmountScrolledUp = 0

  window.addEventListener('scroll', () => {
    // The number of pixels the user has scrolled down from the top of the page
    const currentScrollTop =
      document.body.scrollTop || document.documentElement.scrollTop

    // If scrolling down
    if (currentScrollTop > previousScrollTop) {
      cumulativeAmountScrolledUp = 0
      cumulativeAmountScrolledDown += currentScrollTop - previousScrollTop
      // If you have been scrolling down for more than ${scrollDownThreshold}px
      if (cumulativeAmountScrolledDown > scrollDownThreshold) {
        // If you have passed the threshold where the navbar should not disappear
        if (currentScrollTop > threshold) {
          slideNavbarUp()
        }
      }
    }
    // If scrolling up
    else {
      cumulativeAmountScrolledDown = 0
      cumulativeAmountScrolledUp += previousScrollTop - currentScrollTop

      // If you have been scrolling up for more than ${scrollUpThreshold}px
      // OR you are at the top of the page
      if (
        cumulativeAmountScrolledUp > scrollUpThreshold
      ) {
        slideNavbarDown()
      }
      if(currentScrollTop == 0){
        navbar.classList.remove('is-shown')
      }
    }

    previousScrollTop = currentScrollTop
  })

  const slideNavbarUp = () => {
    navbar.style.transform = `translateY(-${navbar.clientHeight}px)`
    navbar.classList.remove('is-shown')
  }

  const slideNavbarDown = () => {
    navbar.style.transform = null
    navbar.classList.add('is-shown')
  }

  window.addEventListener('resize', () => {
  })
}