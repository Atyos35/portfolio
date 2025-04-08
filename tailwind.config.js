/** @type {import('tailwindcss').Config} */
module.exports = {
  mode: 'jit',
  purge: {
    enabled: true,
    content: [
      './dist/**/*.css',
      './dist/**/*.js',
      "./src/**/*.{js,tsx}"
    ],
  },
  theme: {
    extend: {},
  },
  plugins: [],
}
