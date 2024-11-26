export const setCookie = (name, value, expiresInDays) => {
  const expires = new Date()
  expires.setDate(expires.getDate() + expiresInDays)
  document.cookie = `${name}=${value}; expires=${expires.toUTCString()}; Secure`
}

export const cookieExists = name => {
  return !!document.cookie.split('; ').find(row => row.startsWith(name))
}
