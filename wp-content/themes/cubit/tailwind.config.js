const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

const round = num =>
  num
    .toFixed(7)
    .replace(/(\.[0-9]+?)0+$/, '$1')
    .replace(/\.0$/, '')
const rem = px => `${round(px / 16)}rem`

module.exports = {
  content: [
    './404.php',
    './footer.php',
    './header.php',
    './index.php',
    './archive.php',
    './front-page.php',
    './home.php',
    './page.php',
    './single.php',
    './lib/**/*.php',
    './components/**/*.php',
    './woocommerce/**/*.php',
    './src/**/*.{html,js}',
  ],
  safelist: [],
  theme: {
    fontFamily: {
      body: ['OpenSans', ...defaultTheme.fontFamily.sans],
      heading: ['Barlow', ...defaultTheme.fontFamily.serif],
    },

    extend: {
      maxWidth: {
        '8xl': '88rem',
        '9xl': '96rem',
        '10xl': '104rem',
        '11xl': '112rem',
        '12xl': '120rem',
        'contain': '1076px'
      },
      screens: {
        'xs': '370px',
        '4k': '2560px',
      },
      colors: {
      },
      typography: ({ theme }) => ({
        DEFAULT: {
          CSS: {
            maxWidth: '100%',
          },
        },
      }),
    },
  },
  corePlugins: {
    container: false,
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
    require('@tailwindcss/container-queries'),
  ],
}
