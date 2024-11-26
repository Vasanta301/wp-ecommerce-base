document.addEventListener('alpine:init', () => {
  Alpine.data('ModalMember', () => ({
    open:false,
    member: null
  }))
})