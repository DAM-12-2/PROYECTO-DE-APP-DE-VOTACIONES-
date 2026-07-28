<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>@layer base{html,body{margin:0;padding:0;}body{overscroll-behavior:none;}main>:first-child{margin-top:0!important;}main>:last-child{margin-bottom:0!important;}}::-webkit-scrollbar{display:none;}</style>
<script src="https://cdn.tailwindcss.com"></script>
<script id="tailwind-config">tailwind.config={darkMode:"class",theme:{extend:{"colors":{"primary-fixed-dim":"#a7c8ff","inverse-surface":"#2e3132","tertiary":"#00240e","on-error-container":"#93000a","on-background":"#191c1d","on-primary":"#ffffff","surface-container-low":"#f3f4f5","tertiary-container":"#003c1b","surface-variant":"#e1e3e4","on-surface-variant":"#43474f","on-secondary":"#ffffff","outline-variant":"#c3c6d1","error-container":"#ffdad6","primary-fixed":"#d5e3ff","primary-container":"#003366","on-primary-container":"#799dd6","tertiary-fixed-dim":"#4ae183","inverse-primary":"#a7c8ff","surface-dim":"#d9dadb","surface-container-high":"#e7e8e9","surface-container":"#edeeef","surface":"#f8f9fa","surface-container-highest":"#e1e3e4","on-primary-container":"#799dd6","surface-bright":"#f8f9fa","secondary-fixed-dim":"#e9c400","on-secondary-fixed":"#221b00","tertiary-fixed":"#6bfe9c","secondary-fixed":"#ffe16d","outline":"#737780","on-primary-fixed-variant":"#1f477b","background":"#f8f9fa","on-error":"#ffffff","secondary-container":"#fcd400","secondary":"#705d00","on-tertiary":"#ffffff","on-tertiary-fixed":"#00210c","surface-tint":"#3a5f94","surface-container-lowest":"#ffffff","on-secondary-container":"#6e5c00","inverse-on-surface":"#f0f1f2","on-primary-fixed":"#001b3c","primary":"#001e40","on-tertiary-fixed-variant":"#005228","on-secondary-fixed-variant":"#544600","on-surface":"#191c1d","error":"#ba1a1a"},"borderRadius":{"DEFAULT":"0.125rem","lg":"0.25rem","xl":"0.5rem","full":"0.75rem"},"spacing":{"section-margin":"40px","card-gap":"20px","gutter":"16px","container-padding":"24px","base":"8px"},"fontFamily":{"body-md":["Inter"],"headline-sm":["Inter"],"label-sm":["Inter"],"headline-lg-mobile":["Inter"],"body-lg":["Inter"],"label-lg":["Inter"],"headline-md":["Inter"],"headline-lg":["Inter"]},"fontSize":{"body-md":["14px",{"lineHeight":"20px","fontWeight":"400"}],"headline-sm":["20px",{"lineHeight":"28px","fontWeight":"600"}],"label-sm":["12px",{"lineHeight":"16px","fontWeight":"500"}],"headline-lg-mobile":["24px",{"lineHeight":"32px","fontWeight":"700"}],"body-lg":["16px",{"lineHeight":"24px","fontWeight":"400"}],"label-lg":["14px",{"lineHeight":"20px","fontWeight":"600"}],"headline-md":["24px",{"lineHeight":"32px","fontWeight":"600"}],"headline-lg":["30px",{"lineHeight":"38px","letterSpacing":"-0.02em","fontWeight":"700"}]}}}}</script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&amp;display=swap" rel="stylesheet"/>
@stack('head')
</head>
<body class="bg-background font-body-md text-on-background">
@yield('content')
</body>
</html>