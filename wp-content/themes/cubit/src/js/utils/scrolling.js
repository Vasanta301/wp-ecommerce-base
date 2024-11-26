export const enableScrolling = () => {
  document.documentElement.classList.remove('disable-scrolling')
  document.body.classList.remove('disable-scrolling')
}

export const disableScrolling = () => {
  document.documentElement.classList.add('disable-scrolling')
  document.body.classList.add('disable-scrolling')
}

export const toggleScrolling = () => {
  if (!document.documentElement.classList.contains('disable-scrolling')) {
    document.documentElement.classList.add('disable-scrolling')
    document.body.classList.add('disable-scrolling')
  } else {
    document.documentElement.classList.remove('disable-scrolling')
    document.body.classList.remove('disable-scrolling')
  }
}