const colors = require('tailwindcss/colors')

module.exports = {
    purge: [],
    darkMode: false, // or 'media' or 'class'
    theme: {
        colors: {
            gray: colors.blueGray,
            blue: colors.cyan,
            green: colors.emerald,
            red: colors.red,
            yellow: colors.amber,
            white: colors.white,
            black: colors.black
        },
        fontFamily: {
            'jura': 'Jura',
            'sans': 'Roboto'
        },
        extend: {
            transform: ['hover'],
        },
    },
    variants: {
        extend: {},
    },
    plugins: [],
}
