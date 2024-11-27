export default elementId => {
  const navbar = document.getElementById('header-container'); // Must have position: fixed
  const navbarHeader = document.getElementById('header');
  const threshold = 2 * navbarHeader.clientHeight; // Number of px at the top of the page where navbar won't disappear

  const scrollDownThreshold = 25; // How many pixels to scroll before hiding
  const scrollUpThreshold = 100;
  const placeholder = document.getElementById('header-placeholder');

  navbar.style.position = 'fixed';

  navbar.dataset.initialClass = navbar.getAttribute('class');
  const transition = window
    .getComputedStyle(navbar)
    .getPropertyValue('transition');
  navbar.style.transition = [transition, '0.3s transform ease-in-out'].join(',');

  let previousScrollTop = 0;
  let cumulativeAmountScrolledDown = 0;
  let cumulativeAmountScrolledUp = 0;

  window.addEventListener('scroll', () => {
    // Exit early if the modal is open
    if (document.body.classList.contains('modal-open')) {
      return; // Prevent hiding or showing the navbar
    }

    const currentScrollTop =
      document.body.scrollTop || document.documentElement.scrollTop;

    // If scrolling down
    if (currentScrollTop > previousScrollTop) {
      cumulativeAmountScrolledUp = 0;
      cumulativeAmountScrolledDown += currentScrollTop - previousScrollTop;

      if (cumulativeAmountScrolledDown > scrollDownThreshold) {
        if (currentScrollTop > threshold) {
          slideNavbarUp();
        }
      }
    }
    // If scrolling up
    else {
      cumulativeAmountScrolledDown = 0;
      cumulativeAmountScrolledUp += previousScrollTop - currentScrollTop;

      if (cumulativeAmountScrolledUp > scrollUpThreshold || currentScrollTop === 0) {
        slideNavbarDown();
      }
    }

    previousScrollTop = currentScrollTop;
  });

  const slideNavbarUp = () => {
    navbar.style.transform = `translateY(-${navbar.clientHeight}px)`;
    navbar.classList.remove('is-shown');
  };

  const slideNavbarDown = () => {
    navbar.style.transform = null;
    navbar.classList.add('is-shown');
  };

  window.addEventListener('resize', () => {
    // Optional: Adjustments for responsiveness
  });
};
