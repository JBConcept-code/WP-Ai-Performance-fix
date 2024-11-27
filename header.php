<?php
/**
 * The header template for the theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2">
    <?php wp_head(); ?>

    <link rel="preload" href="https://jbconcept.ro/wp-content/uploads/2024/11/about_creare-site-web.webp" as="image">

    <!-- Preload essential fonts -->
    <link rel="preload" href="https://jbconcept.ro/wp-content/themes/arolax/assets/fonts/icomoon.ttf?nh9vrv" as="font" type="font/ttf" crossorigin="anonymous">
    <link rel="preload" href="https://fonts.gstatic.com/s/roboto/v32/KFOmCnqEu92Fr1Mu7WxKOzY.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="https://fonts.gstatic.com/s/kanit/v15/nKKU-Go6G5tXcr4-ORWnVaE.woff2" as="font" type="font/woff2" crossorigin="anonymous">

    <!-- Load GSAP and ScrollTrigger asynchronously for better performance -->
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <!-- Modern script with fallback -->
    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            const tracking = {
                gtmId: 'GTM-9936886831',
                fbPixelId: '1546752002768390',
                measurementId: 'G-8GK6634RBN',

                init() {
                    // Initialize data layers
                    window.dataLayer = window.dataLayer ?? [];
                    window.fbq = window.fbq ?? (...args) => (window.fbq.q = window.fbq.q ?? []).push(args);

                    // Lazy-load GTM after main content has loaded
                    window.addEventListener('load', () => {
                        const gtmScript = document.createElement('script');
                        gtmScript.src = `https://www.googletagmanager.com/gtm.js?id=${this.gtmId}`;
                        gtmScript.defer = true;
                        document.head.appendChild(gtmScript);
                    });

                    // Lazy-load FB Pixel after main content has loaded
                    window.addEventListener('load', () => {
                        const fbScript = document.createElement('script');
                        fbScript.src = 'https://connect.facebook.net/en_US/fbevents.js';
                        fbScript.defer = true;
                        document.head.appendChild(fbScript);

                        // Initialize FB Pixel
                        fbq('init', this.fbPixelId);
                        fbq('track', 'PageView');
                    });

                    this.initializeEvents();
                },

                track(event, params = {}) {
                    // GTM
                    window.dataLayer?.push({
                        event,
                        measurement_id: this.measurementId,
                        ...params
                    });

                    // FB
                    window.fbq?.('track', event, params);
                },

                commerce: {
                    view(product) {
                        tracking.track('ViewProduct', product);
                    },
                    cart(product) {
                        tracking.track('AddToCart', product);
                    },
                    purchase(order) {
                        tracking.track('Purchase', order);
                    }
                },

                initializeEvents() {
                    // Form submissions
                    document.querySelectorAll('form').forEach(form => {
                        form.addEventListener('submit', () => this.track('FormSubmit', {
                            form_id: form.id || 'unnamed_form'
                        }));
                    });

                    // Click tracking with event delegation
                    document.addEventListener('click', ({target}) => {
                        const button = target.closest('button, a.track-click');
                        if (button) {
                            this.track('ButtonClick', {
                                text: button.textContent?.trim(),
                                id: button.id
                            });
                        }
                    }, { passive: true });

                    // Scroll tracking with IntersectionObserver
                    const scrollMarks = new Set();
                    const observeScroll = new IntersectionObserver(
                        (entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    const depth = parseInt(entry.target.dataset.depth);
                                    if (!scrollMarks.has(depth)) {
                                        scrollMarks.add(depth);
                                        this.track('ScrollDepth', { depth });
                                    }
                                }
                            });
                        },
                        { threshold: [0] }
                    );

                    // Create scroll markers
                    [25, 50, 75, 90].forEach(depth => {
                        const marker = document.createElement('div');
                        marker.style.position = 'absolute';
                        marker.style.top = `${depth}vh`;
                        marker.style.height = '1px';
                        marker.style.width = '1px';
                        marker.style.opacity = '0';
                        marker.dataset.depth = depth;
                        document.body.appendChild(marker);
                        observeScroll.observe(marker);
                    });
                }
            };

            // Lazy-load tracking only after user interaction
            let trackingInitialized = false;
            const startTracking = () => {
                if (!trackingInitialized) {
                    tracking.init();
                    trackingInitialized = true;
                }
            };

            // Trigger tracking initialization on user interaction
            ['click', 'scroll', 'mousemove', 'touchstart'].forEach(event => {
                window.addEventListener(event, startTracking, { once: true });
            });
        });
    </script>

    <!-- Legacy browsers fallback -->
    <script nomodule defer>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.defer=true;
        j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-9936886831');

        // FB Pixel legacy initialization
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.defer=true;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1546752002768390');
        fbq('track', 'PageView');
    </script>

    <!-- GTM NoScript -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-9936886831"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>

    <!-- Facebook Pixel NoScript -->
    <noscript>
        <img height="1" width="1" style="display:none"
             src="https://www.facebook.com/tr?id=1546752002768390&ev=PageView&noscript=1"/>
    </noscript>

    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <!-- Combine font requests and use font-display swap -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Kanit:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Font loading strategy -->
    <script>
        // Font faces configuration
        const fonts = [
            {
                family: 'Roboto',
                weights: ['400', '700'],
                display: 'swap',
                unicode: 'U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD'
            },
            {
                family: 'Kanit',
                weights: ['400', '500', '600'],
                display: 'swap',
                unicode: 'U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD'
            },
            // Add other fonts following the same pattern
        ];

        // Function to load fonts efficiently
        function loadFonts() {
            // Create a style element for font-face definitions
            const style = document.createElement('style');
            let fontFaces = '';

            fonts.forEach(font => {
                font.weights.forEach(weight => {
                    fontFaces += `
                        @font-face {
                            font-family: '${font.family}';
                            font-style: normal;
                            font-weight: ${weight};
                            font-display: ${font.display};
                            src: url(https://fonts.gstatic.com/s/${font.family.toLowerCase()}/v1/fontfile-${weight}.woff2) format('woff2');
                            unicode-range: ${font.unicode};
                        }
                    `;
                });
            });

            style.textContent = fontFaces;
            document.head.appendChild(style);
        }

        // Load fonts when the page becomes interactive
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', loadFonts);
        } else {
            loadFonts();
        }

        // Optional: Implement font loading observer
        if ('FontFace' in window) {
            fonts.forEach(font => {
                font.weights.forEach(weight => {
                    const fontFace = new FontFace(
                        font.family,
                        `url(https://fonts.gstatic.com/s/${font.family.toLowerCase()}/v1/fontfile-${weight}.woff2) format('woff2')`,
                        { weight, display: font.display }
                    );

                    fontFace.load().then(() => {
                        document.fonts.add(fontFace);
                    }).catch(err => {
                        console.warn(`Failed to load ${font.family} (weight: ${weight}):`, err);
                    });
                });
            });
        }
    </script>

    <!-- CSS optimization for font loading -->
    <style>
        /* Fallback font metrics optimization */
        :root {
            --font-fallback: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        /* Optimize font loading with size-adjust and ascent-override */
        @font-face {
            font-family: 'Roboto Fallback';
            size-adjust: 97.5%;
            ascent-override: 93%;
            descent-override: 23%;
            line-gap-override: 0%;
            src: local(--font-fallback);
        }

        /* Apply fallback first */
        body {
            font-family: 'Roboto Fallback', var(--font-fallback);
        }

        /* Font classes will be updated once fonts are loaded */
        .fonts-loaded body {
            font-family: 'Roboto', var(--font-fallback);
        }
    </style>
</head>
<body <?php body_class(); ?>>
    <?php
    wp_body_open();
    get_template_part('template-parts/headers/header');
    ?>