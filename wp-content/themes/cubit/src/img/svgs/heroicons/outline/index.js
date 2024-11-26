const fs = require('fs')
const files = fs.readdirSync(__dirname)
files.forEach(file => {
  const key = file.split('.')[0]
  const value = key
    .split('-')
    .map(word => word[0].toUpperCase() + word.substring(1))
    .join(' ')
  fs.appendFileSync('icons.txt', `${key} : ${value}\n`)
})
