/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        black: "var(--black)",
        darkpurple: "var(--darkpurple)",
        descedent: "var(--descedent)",
        purple: "var(--purple)",
      },
      fontFamily: {
        text: "var(--text-font-family)",
      },
    },
  },
  plugins: [],
}; 