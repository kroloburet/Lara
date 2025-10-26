
<!--- Scroller Component --->
<section id="scrollerComponent" class="UI_no-scrollbar">
    <div class="scrollerComponent_wrapper">
        <a href="https://www.coffee.ua/" target="_blank">
            <img src="/images/scroller/coffee.png" alt="coffee">
        </a>
        <a href="https://www.coffee.ua/" target="_blank">
            <img src="/images/scroller/coffee.png" alt="coffee">
        </a>
        <a href="https://www.coffee.ua/" target="_blank">
            <img src="/images/scroller/coffee.png" alt="coffee">
        </a>
        <a href="https://www.coffee.ua/" target="_blank">
            <img src="/images/scroller/coffee.png" alt="coffee">
        </a>
        <a href="https://www.coffee.ua/" target="_blank">
            <img src="/images/scroller/coffee.png" alt="coffee">
        </a>
        <a href="https://www.coffee.ua/" target="_blank">
            <img src="/images/scroller/coffee.png" alt="coffee">
        </a>
        <a href="https://www.coffee.ua/" target="_blank">
            <img src="/images/scroller/coffee.png" alt="coffee">
        </a>
        <a href="https://www.coffee.ua/" target="_blank">
            <img src="/images/scroller/coffee.png" alt="coffee">
        </a>
        <a href="https://www.coffee.ua/" target="_blank">
            <img src="/images/scroller/coffee.png" alt="coffee">
        </a>
        <a href="https://www.coffee.ua/" target="_blank">
            <img src="/images/scroller/coffee.png" alt="coffee">
        </a>
        <a href="https://www.coffee.ua/" target="_blank">
            <img src="/images/scroller/coffee.png" alt="coffee">
        </a>
        <a href="https://www.coffee.ua/" target="_blank">
            <img src="/images/scroller/coffee.png" alt="coffee">
        </a>
        <a href="https://www.coffee.ua/" target="_blank">
            <img src="/images/scroller/coffee.png" alt="coffee">
        </a>
        <a href="https://www.coffee.ua/" target="_blank">
            <img src="/images/scroller/coffee.png" alt="coffee">
        </a>
        <a href="https://www.coffee.ua/" target="_blank">
            <img src="/images/scroller/coffee.png" alt="coffee">
        </a>
        <a href="https://www.coffee.ua/" target="_blank">
            <img src="/images/scroller/coffee.png" alt="coffee">
        </a>
    </div>
</section>

@pushOnce('startPage')

    <!--
    ########### Scroller Component
    -->

    <style>
        #scrollerComponent {
            display: flex;
            align-items: center;
            padding: var(--layout-gap-xs);
            width: 100%;
            overflow-x: auto;
            position: relative;
            background-color: var(--UI_base-body-bg-color);
            border: var(--UI_base-border);
            border-radius: var(--UI_base-border-radius-l);
        }

        .scrollerComponent_wrapper {
            display: flex;
            align-items: center;
            gap: var(--layout-gap-l);
        }

        .scrollerComponent_wrapper img {
            max-width: none;
            height: clamp(60px, 6vw, 150px);
            filter: grayscale(1);
            transition: filter 0.3s ease;
        }

        .scrollerComponent_wrapper img:hover {
            filter: grayscale(0);
        }
    </style>
@endPushOnce

@pushOnce('endPage')

    <!--
    ########### Scroller Component
    -->

    <script>
        {
            const scroller = document.getElementById('scrollerComponent');

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
