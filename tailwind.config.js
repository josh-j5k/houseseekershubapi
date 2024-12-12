/** @type {import('tailwindcss').Config} */
import defaultTheme from "tailwindcss/defaultTheme"
module.exports = {
	content: [
		"./components/**/*.{js,vue,ts}",
		"./layouts/**/*.vue",
		"./pages/**/*.vue",
		"./plugins/**/*.{js,ts}",
		"./app.vue",
		"./error.vue",
	],
	theme: {
		extend: {
			fontFamily: {
				sans: ["Roboto", ...defaultTheme.fontFamily.sans],
			},
			animation: {
				slide_in_from_left: "slide_in_from_left ease 500ms forwards",
				slide_in_from_right: "slide_in_from_right ease 500ms forwards",
				slide_in_from_top: "slide_in_from_top ease 500ms forwards",
				slide_in_from_bottom: "slide_in_from_bottom ease 500ms forwards",
				slide_out_to_left: "slide_out_to_left ease 500ms forwards",
				slide_out_to_right: "slide_out_to_right ease 500ms forwards",
				slide_out_to_top: "slide_out_to_top ease 500ms forwards",
				slide_out_to_bottom: "slide_out_to_bottom ease 500ms forwards",
			},
			keyframes: {
				slide_in_from_left: {
					"0%": { transform: "translateX(-100%)" },
					"100%": { transform: "translateX(0)" },
				},
				slide_in_from_top: {
					"0%": { transform: "translateY(-100%)" },
					"100%": { transform: "translateY(0)" },
				},
				slide_in_from_right: {
					"0%": { transform: "translateX(100%)" },
					"100%": { transform: "translateX(0)" },
				},
				slide_in_from_bottom: {
					"0%": { transform: "translateY(100%)" },
					"100%": { transform: "translateY(0)" },
				},
				slide_out_to_bottom: {
					"0%": { transform: "translateY(0)" },
					"100%": { transform: "translateY(100%)" },
				},
				slide_out_to_top: {
					"0%": { transform: "translateY(0)" },
					"100%": { transform: "translateY(-100%)" },
				},
				slide_out_to_left: {
					"0%": { transform: "translateX(0)" },
					"100%": { transform: "translateX(-100%)" },
				},
				slide_out_to_right: {
					"0%": { transform: "translateX(0)" },
					"100%": { transform: "translateX(100%)" },
				},
			},
			screens: {
				"-2xl": { max: "1535px" },
				// => @media (max-width: 1536px) { ... }
				"-xl": { max: "1279px" },
				// => @media (max-width: 1279px) { ... }
				"-lg": { max: "1023px" },
				// => @media (max-width: 1023px) { ... }
				"-md": { max: "768px" },
				// => @media (max-width: 769px) { ... }
				"-sm": { max: "639px" },
				// => @media (max-width: 639px) { ... }
				tablet: { min: "639px", max: "1023px" },
				// => @media (width: > 639 <= 1023) { ... }
			},
			colors: {
				accent: "#175fef",
				"accent-hover": "#4072d6",
				secondary: "#5f17ef",
				"black-opacity-50": "rgba(0, 0, 0, 0.5)",
				"black-opacity-80": "rgba(0, 0, 0, 0.8)",
				"dark-blue": "#0c0c1f",
			},
			zIndex: {
				max: 9999,
			},
			content: {
				emptystring: "''",
			},
		},
	},
	plugins: [],
}
