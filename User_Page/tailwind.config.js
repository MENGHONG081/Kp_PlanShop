// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        'background-green': '#4CAF50', // example green
        'background-dark': '#1a1a1a',  // example dark
      },
    },
  },
}

// tailwind.config.js
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
    // Add any other files that contain Tailwind classes
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
