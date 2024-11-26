/**
 * See https://stackoverflow.com/a/24004942/11784757
 */
export default function (func, wait, immediate = true) {
  let timeout
  return () => {
    const context = this
    const args = arguments
    const callNow = immediate && !timeout
    clearTimeout(timeout)
    timeout = setTimeout(function () {
      timeout = null
      if (!immediate) {
        func.apply(context, args)
      }
    }, wait)
    if (callNow) func.apply(context, args)
  }
}
