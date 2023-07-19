/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      backdropBlur: {
        // 使用伪元素来模拟一个背景
        'sm': 'blur(4px) brightness(80%)',
        'md': 'blur(8px) brightness(70%)',
        'lg': 'blur(12px) brightness(60%)',
        'xl': 'blur(16px) brightness(50%)',
        '2xl': 'blur(20px) brightness(40%)',
      }
    },
  },
  plugins: [],
  important: true,
}

