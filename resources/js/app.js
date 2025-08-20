import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";

// css
import "../css/reset.css";
import "../css/style.css";
import "a11y-slider/dist/a11y-slider.css";

// js
import "../../public/js/script.js";
import "./side-bar.js";
import "./footer.js";
import A11YSlider from "a11y-slider";

// slider
const slider = new A11YSlider(document.querySelector(".slider"), {
    adaptiveHeight: true,
    dots: true,
    arrows: false,
    autoplay: true,
    autoplaySpeed: 3000,
    responsive: {
        768: {
            // min-width: 48em
            arrows: true,
        },
        992: {
            // min-width: 62em
            arrows: true,
        },
    },
    customPaging: function (index, a11ySlider) {
        return '<button class="custom-slider-dot"></button>';
    },
});
