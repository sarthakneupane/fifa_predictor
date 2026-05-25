/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      },
      colors: {
        brand: {
          blue: '#1e3a8a',
          blueLight: '#3b82f6',
          red: '#dc2626',
          redLight: '#ef4444',
        },
      },
      boxShadow: {
        elegant: '0 20px 50px -20px rgba(30,58,138,0.35)',
        card: '0 10px 30px -12px rgba(30,58,138,0.18)',
      },
      backgroundImage: {
        'gradient-primary': 'linear-gradient(135deg, #1e3a8a, #3b82f6)',
        'gradient-accent': 'linear-gradient(135deg, #ef4444, #dc2626)',
        'gradient-hero': 'linear-gradient(135deg, rgba(30,58,138,0.92), rgba(59,130,246,0.55) 60%, rgba(59,130,246,0.1))',
      },
    },
  },
  plugins: [],
}
