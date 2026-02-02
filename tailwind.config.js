import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                'spinneys': {
                    'green': {
                        DEFAULT: '#0B4C2D',
                        50: '#E6F3ED',
                        100: '#CCE7DB',
                        200: '#99CFB7',
                        300: '#66B793',
                        400: '#339F6F',
                        500: '#0B4C2D',
                        600: '#093D24',
                        700: '#072E1B',
                        800: '#051F12',
                        900: '#020F09',
                    },
                    'gold': {
                        DEFAULT: '#D4AF37',
                        50: '#FAF7EB',
                        100: '#F5EFD7',
                        200: '#EBDFAF',
                        300: '#E0CF87',
                        400: '#D6BF5F',
                        500: '#D4AF37',
                        600: '#AA8C2C',
                        700: '#7F6921',
                        800: '#554616',
                        900: '#2A230B',
                    },
                    'off-white': '#F8F8F6',
                    'charcoal': '#2C2C2C',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
