<!--- Partners Scroller Component --->
<section id="partnersScrollerComponent" class="UI_no-scrollbar">
    <div class="partnersScrollerComponent_wrapper">
        <a href="https://www.flying-age.com" target="_blank">
            <img src="/images/partners/FlyingAge.png" alt="Flying age">
        </a>
        <a href="https://smartfusiontech.ai" target="_blank">
            <img src="/images/partners/experience.png" alt="Smartfusiontech">
        </a>
        <a href="https://www.assuaged.com" target="_blank">
            <img src="/images/partners/assuaged_foundation.png" alt="Assuaged">
        </a>
        <a href="https://v-tylu.work" target="_blank">
            <img src="/images/partners/robota_v_tylu.png" alt="V tylu">
        </a>
        <a href="https://v-tylu.com/mentory" target="_blank">
            <img src="/images/partners/mentoru_v_tylu.png" alt="V tylu">
        </a>
        <a href="https://lpnu.ua" target="_blank">
            <img src="/images/partners/nulp.png" alt="Lpnu">
        </a>
        <a href="https://tsus.lpnu.ua/uk/sid" target="_blank">
            <img src="/images/partners/sid.png" alt="Tsus">
        </a>
        <a href="https://tsus.lpnu.ua" target="_blank">
            <img src="/images/partners/tech_sturtup_school.png" alt="Tsus">
        </a>
        <a href="https://wiseboard.me" target="_blank">
            <img src="/images/partners/wiseboard.png" alt="Wiseboard">
        </a>
        <a href="https://www.successcharge.com/" target="_blank">
            <img src="/images/partners/success_charge_academy.png" alt="Success charge academy">
        </a>
        <a href="https://inveritasoft.com/" target="_blank">
            <img src="/images/partners/InVerita.png" alt="InVerita">
        </a>
        <a href="https://fashionstock.com/" target="_blank">
            <img src="/images/partners/FashionStock.png" alt="Fashion Stock">
        </a>
    </div>
</section>

@pushOnce('startPage')

    <!--
    ########### Partners Scroller Component
    -->

    <style>
        #partnersScrollerComponent {
            display: flex;
            align-items: center;
            padding: var(--layout-gap-xs);
            width: 100%;
            overflow-x: auto;
            position: relative;
            background-color: var(--tertiary-bg-color);
            border: var(--UI_base-border);
        }

        .partnersScrollerComponent_wrapper {
            display: flex;
            align-items: center;
            gap: var(--layout-gap-l);
        }

        .partnersScrollerComponent_wrapper img {
            max-width: none;
            height: clamp(60px, 6vw, 150px);
            filter: grayscale(1);
            transition: filter 0.3s ease;
        }

        .partnersScrollerComponent_wrapper img:hover {
            filter: grayscale(0);
        }
    </style>
@endPushOnce

@pushOnce('endPage')

    <!--
    ########### Partners Scroller Component
    -->

    <script>
        {
            const scroller = document.getElementById('partnersScrollerComponent');

            let isHovered = false;
            let autoScrollInterval;
            let scrollDirection = 1; // 1 for forward, -1 for backward

            // Function to handle automatic scrolling
            function startAutoScroll() {
                autoScrollInterval = setInterval(() => {
                    if (!isHovered) {
                        scroller.scrollLeft += scrollDirection; // Adjust speed if needed

                        if (scroller.scrollLeft >= scroller.scrollWidth - scroller.clientWidth) {
                            scrollDirection = -1; // Reverse direction
                        } else if (scroller.scrollLeft <= 0) {
                            scrollDirection = 1; // Forward direction
                        }
                    }
                }, 40); // Adjust interval for smoother scrolling
            }

            function stopAutoScroll() {
                clearInterval(autoScrollInterval);
            }

            // Pause auto-scroll on hover
            scroller.addEventListener('mouseenter', () => {
                isHovered = true;
                stopAutoScroll();
            });

            // Resume auto-scroll on mouse leave
            scroller.addEventListener('mouseleave', () => {
                isHovered = false;
                startAutoScroll();
            });

            // Allow manual scrolling without interference
            scroller.addEventListener('scroll', () => {
                if (isHovered) {
                    stopAutoScroll();
                }
            });

            // Start auto-scroll on load
            startAutoScroll();
        }
    </script>
@endPushOnce
