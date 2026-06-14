// PHANDAHUB JavaScript functionality

document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("searchInput");
    const productCards = document.querySelectorAll(".product-card");
    const cartButtons = document.querySelectorAll(".add-to-cart");

    // Search products by product name or description
    if (searchInput) {
        searchInput.addEventListener("keyup", function () {
            let searchValue = searchInput.value.toLowerCase();

            productCards.forEach(function (card) {
                let productText = card.innerText.toLowerCase();

                if (productText.includes(searchValue)) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        });
    }

    // Add product to cart confirmation
    cartButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            alert("Item added to cart successfully.");
        });
    });

});