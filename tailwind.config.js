/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php', // Scans Blade files for Tailwind classes
    './resources/**/*.js',        // Scans JavaScript files for Tailwind classes
    './resources/**/*.vue',       // If using Vue
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

