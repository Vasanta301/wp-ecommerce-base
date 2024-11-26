export default () => {
  const buttons = document.querySelectorAll(
    '.btn-primary,.btn-primary-outline, .btn-secondary, .wpcf7 button'
  )
  if (buttons.length > 0) {
    buttons.forEach(button => {
      button.classList.add('group')
      let translateClass1 = ''
      let translateClass2 = ''
      if (button.classList.contains('big')) {
        translateClass1 = 'group-hover:-translate-y-12'
        translateClass2 = 'translate-y-12'
      } else {
        translateClass1 = 'group-hover:-translate-y-6'
        translateClass2 = 'translate-y-6'
      }
      button.innerHTML = `
        <span class="block relative overflow-hidden">
          <span class="absolute inset-0 transition-all duration-300 translate-y-0 ${translateClass1}">
          ${button.innerHTML}
          </span>
          <span class="invisible">
            ${button.innerHTML}
          </span>
          <span class="absolute inset-0 transition-all duration-300  group-hover:translate-y-0 ${translateClass2}">
          ${button.innerHTML}
          </span>
        </span>
      `
    })
  }
}
