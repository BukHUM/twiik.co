/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './inc/**/*.php',
    './template-parts/**/*.php',
    './widgets/**/*.php',
    './assets/js/**/*.js',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['"Google Sans"', '"Noto Sans Thai"', 'sans-serif'],
        display: ['"Google Sans"', '"Noto Sans Thai"', 'sans-serif'],
      },
      colors: {
        'google-blue': '#1a73e8',
        'google-gray': '#202124',
        'google-gray-500': '#5f6368',
        'google-gray-100': '#f1f3f4',
        'google-gray-50': '#f8f9fa',
        accent: '#1a73e8', /* alias ตรง mockup; class เดิมเช่น text-accent ยังใช้ได้ */
        primary: '#202124',
        'news-tech': '#3B82F6',
        'news-ent': '#EC4899',
        'news-fin': '#10B981',
        'news-sport': '#F59E0B',
      },
      borderRadius: {
        card: '24px',
        pill: '100px',
      },
      boxShadow: {
        card: '0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15)',
        'card-hover': '0 1px 3px 0 rgba(60,64,67,0.3), 0 4px 8px 3px rgba(60,64,67,0.15)',
      },
      typography: {
        DEFAULT: {
          css: {
            maxWidth: 'none',
            color: '#202124',
            lineHeight: '1.75',
            fontSize: '1.125rem',
            'h1, h2, h3, h4, h5, h6': {
              color: '#202124',
              fontWeight: '700',
              lineHeight: '1.2',
            },
            p: {
              marginTop: '1.25em',
              marginBottom: '1.25em',
            },
            a: {
              color: '#1a73e8',
              textDecoration: 'underline',
              '&:hover': {
                color: '#1557b0',
              },
            },
            strong: {
              fontWeight: '600',
              color: '#202124',
            },
            'ul, ol': {
              marginTop: '1.25em',
              marginBottom: '1.25em',
              paddingLeft: '1.625em',
            },
            li: {
              marginTop: '0.5em',
              marginBottom: '0.5em',
            },
            blockquote: {
              borderLeftColor: '#1a73e8',
              borderLeftWidth: '4px',
              paddingLeft: '1em',
              fontStyle: 'italic',
              color: '#5f6368',
            },
            img: {
              borderRadius: '24px',
              marginTop: '2em',
              marginBottom: '2em',
            },
          },
        },
      },
    },
  },
  plugins: [
    function ({ addComponents, theme }) {
      addComponents({
        '.prose': {
          '& p': {
            marginTop: theme('spacing.5'),
            marginBottom: theme('spacing.5'),
            lineHeight: theme('lineHeight.relaxed'),
          },
          '& h1, & h2, & h3, & h4, & h5, & h6': {
            marginTop: theme('spacing.6'),
            marginBottom: theme('spacing.4'),
            fontWeight: theme('fontWeight.bold'),
            lineHeight: theme('lineHeight.tight'),
            color: theme('colors.gray.900'),
          },
          '& h1': { fontSize: theme('fontSize.3xl[0]') },
          '& h2': { fontSize: theme('fontSize.2xl[0]') },
          '& h3': { fontSize: theme('fontSize.xl[0]') },
          '& a': {
            color: theme('colors.google-blue'),
            textDecoration: 'underline',
            '&:hover': {
              color: theme('colors.blue.700'),
            },
          },
          '& strong': {
            fontWeight: theme('fontWeight.semibold'),
            color: theme('colors.gray.900'),
          },
          '& ul, & ol': {
            marginTop: theme('spacing.5'),
            marginBottom: theme('spacing.5'),
            paddingLeft: theme('spacing.6'),
          },
          '& li': {
            marginTop: theme('spacing.2'),
            marginBottom: theme('spacing.2'),
          },
          '& blockquote': {
            borderLeftWidth: '4px',
            borderLeftColor: theme('colors.google-blue'),
            paddingLeft: theme('spacing.4'),
            fontStyle: 'italic',
            color: theme('colors.gray.600'),
            marginTop: theme('spacing.6'),
            marginBottom: theme('spacing.6'),
          },
          '& img': {
            borderRadius: theme('borderRadius.lg'),
            marginTop: theme('spacing.8'),
            marginBottom: theme('spacing.8'),
            maxWidth: '100%',
            height: 'auto',
          },
          '& code': {
            backgroundColor: theme('colors.gray.100'),
            padding: theme('spacing.1'),
            borderRadius: theme('borderRadius.sm'),
            fontSize: theme('fontSize.sm[0]'),
          },
          '& pre': {
            backgroundColor: theme('colors.gray.900'),
            color: theme('colors.gray.100'),
            padding: theme('spacing.4'),
            borderRadius: theme('borderRadius.lg'),
            overflow: 'auto',
            marginTop: theme('spacing.6'),
            marginBottom: theme('spacing.6'),
          },
        },
      });
    },
  ],
};
