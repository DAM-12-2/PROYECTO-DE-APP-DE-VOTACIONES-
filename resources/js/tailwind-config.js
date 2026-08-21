tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "on-primary": "#ffffff",
                "surface-container-low": "#f3f4f5",
                "surface-dim": "#d9dadb",
                "surface-container-high": "#e7e8e9",
                "secondary-fixed": "#ffe16d",
                "on-secondary-container": "#6e5c00",
                "on-background": "#191c1d",
                "primary-fixed": "#d5e3ff",
                "on-secondary-fixed-variant": "#544600",
                "on-tertiary-fixed": "#00210c",
                "surface-container": "#edeeef",
                "on-primary-fixed": "#001b3c",
                "primary-fixed-dim": "#a7c8ff",
                "surface-container-highest": "#e1e3e4",
                "secondary-container": "#fcd400",
                "on-primary-container": "#799dd6",
                "error-container": "#ffdad6",
                "on-error": "#ffffff",
                "secondary-fixed-dim": "#e9c400",
                "outline": "#737780",
                "surface-tint": "#3a5f94",
                "on-tertiary": "#ffffff",
                "on-tertiary-fixed-variant": "#005228",
                "surface-container-lowest": "#ffffff",
                "primary-container": "#003366",
                "outline-variant": "#c3c6d1",
                "tertiary-fixed": "#6bfe9c",
                "secondary": "#705d00",
                "surface-bright": "#f8f9fa",
                "on-surface": "#191c1d",
                "surface": "#f8f9fa",
                "tertiary-container": "#003c1b",
                "on-secondary-fixed": "#221b00",
                "tertiary": "#00240e",
                "primary": "#001e40",
                "on-surface-variant": "#43474f",
                "inverse-primary": "#a7c8ff",
                "error": "#ba1a1a",
                "tertiary-fixed-dim": "#4ae183",
                "on-primary-fixed-variant": "#1f477b",
                "on-tertiary-container": "#00b35d",
                "background": "#f8f9fa",
                "on-secondary": "#ffffff",
                "inverse-on-surface": "#f0f1f2",
                "surface-variant": "#e1e3e4",
                "on-error-container": "#93000a",
                "inverse-surface": "#2e3132"
            },
            borderRadius: {
                DEFAULT: "0.125rem",
                lg: "0.25rem",
                xl: "0.5rem",
                full: "0.75rem"
            },
            spacing: {
                "container-padding": "24px",
                gutter: "16px",
                "card-gap": "20px",
                "section-margin": "40px",
                base: "8px"
            },
            fontFamily: {
                "body-md": ["Inter"],
                "headline-sm": ["Inter"],
                "label-lg": ["Inter"],
                "body-lg": ["Inter"],
                "label-sm": ["Inter"],
                "headline-lg-mobile": ["Inter"],
                "headline-md": ["Inter"],
                "headline-lg": ["Inter"]
            },
            fontSize: {
                "body-md": ["14px", { lineHeight: "20px", fontWeight: "400" }],
                "headline-sm": ["20px", { lineHeight: "28px", fontWeight: "600" }],
                "label-lg": ["14px", { lineHeight: "20px", fontWeight: "600" }],
                "body-lg": ["16px", { lineHeight: "24px", fontWeight: "400" }],
                "label-sm": ["12px", { lineHeight: "16px", fontWeight: "500" }],
                "headline-lg-mobile": ["24px", { lineHeight: "32px", fontWeight: "700" }],
                "headline-md": ["24px", { lineHeight: "32px", fontWeight: "600" }],
                "headline-lg": ["30px", { lineHeight: "38px", letterSpacing: "-0.02em", fontWeight: "700" }]
            }
        }
    }
};
