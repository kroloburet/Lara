<!--- Snow Component (Snow effect for New Year mood) --->
<style>
    #snow-layer {
        --x: 0;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        pointer-events: none;
        z-index: 999;
    }

    .snowflake {
        position: absolute;
        top: -10px;
        color: white;
        font-size: 10px;
        font-family: monospace;
        animation: fall linear infinite;
    }

    @keyframes fall {
        0% {
            transform: translateY(-50vh) translateX(0);
        }
        100% {
            transform: translateY(100vh) translateX(calc(-10vw + 20vw * var(--x)));
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Create snow layer
        const snowLayer = document.createElement("div");
        snowLayer.id = "snow-layer";
        document.body.appendChild(snowLayer);

        // Snowflakes generate
        for (let i = 0; i < 20; i++) {
            const snowflake = document.createElement("div");
            snowflake.className = "snowflake";
            snowflake.textContent = "❄";
            snowflake.style.left = Math.random() * 130 + "vw"; // Random location
            snowflake.style.animationDuration = (Math.random() * 5 + 8) + "s"; // Random fall rate
            snowflake.style.fontSize = (Math.random() * 0.8 + 0.5) + "vw"; // Random size
            snowLayer.appendChild(snowflake);
        }
    });
</script>
