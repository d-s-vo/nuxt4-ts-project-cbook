/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./components/**/*.{js,vue,ts}",
    "./layouts/**/*.vue",
    "./pages/**/*.vue",
    "./plugins/**/*.{js,ts}",
    "./app/**/*.{js,vue,ts}",
    "./nuxt.config.{js,ts}",
    "./app.vue",
    './node_modules/@nuxt/ui/dist/**/*.{js,ts,vue}'
  ],
  theme: {
    extend: {
      fontFamily: {
        'playfair': ['Playfair Display', 'serif'],
        'dancing': ['Dancing Script', 'cursive'],
      }
    },
  },
  plugins: [],
}
