// mở submenu trong thanh sidebar 
window.toggleSubmenu = (btn) => {
    btn.nextElementSibling.classList.toggle("show");
    btn.classList.toggle("rotate");
}

// đóng thanh sidebar
const sidebar = document.querySelector("#sidebar");

window.closeSidebar = (btn) => {
    sidebar.classList.remove("show");
    sidebar.previousElementSibling.classList.add("visually-hidden");
    sidebar.querySelectorAll(".sub-menu").forEach((item) => {
        item.classList.remove("show");
    })
}

// nhấn nút menu mở thanh sidebar
const mobileToggleNav = document.querySelector(".mobile-toggle-nav");

mobileToggleNav.addEventListener("click", () => {
    sidebar.classList.add("show");
    sidebar.previousElementSibling.classList.remove("visually-hidden");
});
