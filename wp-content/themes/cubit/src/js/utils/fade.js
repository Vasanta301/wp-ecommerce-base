  // Allows animation of elements that need to display: none 
  // in their phased out state
  const fade = elem => {
    if (elem.classList.contains('hidden')) {
      elem.classList.remove('hidden')
      setTimeout(() => {
        elem.classList.remove('visually-hidden')
      }, 20)
    } else {
      elem.classList.add('visually-hidden')
      elem.addEventListener('transitionend', e => {
        elem.classList.add('hidden')
      }, { once: true })
    }
  }
  
  export default fade