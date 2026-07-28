tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            "colors": {
                "primary": "#001e40",
                "on-primary": "#ffffff",
                "primary-container": "#003366",
                "on-primary-container": "#799dd6",
                "secondary": "#505f76",
                "on-secondary": "#ffffff",
                "secondary-container": "#d0e1fb",
                "on-secondary-container": "#54647a",
                "tertiary": "#191f25",
                "on-tertiary": "#ffffff",
                "surface": "#f7f9fb",
                "on-surface": "#191c1e",
                "surface-variant": "#e0e3e5",
                "on-surface-variant": "#43474f",
                "outline": "#737780",
                "outline-variant": "#c3c6d1",
                "background": "#f7f9fb",
                "on-background": "#191c1e",
                "error": "#ba1a1a",
                "error-container": "#ffdad6",
                "on-error-container": "#93000a",
                "primary-fixed": "#d5e3ff",
                "surface-container-low": "#f2f4f6",
                "surface-container-lowest": "#ffffff",
                "surface-container-high": "#e6e8ea"
            },
            "borderRadius": {
                "DEFAULT": "0.125rem",
                "lg": "0.25rem",
                "xl": "0.5rem",
                "full": "0.75rem"
            },
            "spacing": {
                "sidebar-width": "280px",
                "stack-lg": "32px",
                "base": "8px",
                "container-max": "1280px",
                "stack-sm": "8px",
                "stack-md": "16px",
                "margin-mobile": "16px",
                "gutter": "24px"
            },
            "fontFamily": {
                "headline-lg": ["Inter"],
                "body-md": ["Inter"],
                "headline-xl": ["Inter"]
            },
            "fontSize": {
                "headline-xl": ["36px", {"lineHeight": "44px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                "headline-lg": ["28px", {"lineHeight": "36px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                "headline-md": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.05em", "fontWeight": "600"}]
            }
        }
    }
};
