export default () => {
  document.querySelectorAll('input:not([type="checkbox"]), textarea').forEach(input => {
    input.addEventListener('focus', (e) => {
      e.target.labels.forEach(label => {
        label.classList.add('focused')
      })
    })
    input.addEventListener('focusout', (e) => {
      e.target.labels.forEach(label => {
        label.classList.remove('focused')
      })
    })
  })
}