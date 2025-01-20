import { callApi ,showUserMessage} from './config.js';

// Function to fetch and display the products
const fetchProducts = async () => {
    try {
        // Call the API to get the list of products
        const productsResponse = await callApi('getProducts', null);

        // Check if the API response is successful
        if (productsResponse.success) {
            const products = productsResponse.data;

            // Filter out deleted products
            const filteredProducts = products.filter(product => !product.is_deleted);

            // Sort products from new to old by 'created_at'
            const sortedProducts = filteredProducts.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

            // Render products in the DOM
            displayProducts(sortedProducts);
        } else {
            showUserMessage('Error fetching products', 'error');
        }
    } catch (error) {
        console.error('Error fetching products:', error);
        showUserMessage('An error occurred while fetching products', 'error');
    }
};

// Function to render the products in the DOM
const displayProducts = (products) => {
    const productCardsContainer = document.querySelector('.product-cards-container');
    productCardsContainer.innerHTML = ''; // Clear existing products

    // Loop through the products and create HTML for each product
    products.forEach(product => {
        const productCard = document.createElement('div');
        productCard.classList.add('product-card');

        productCard.innerHTML = `
        <img src="${product.image_url}" alt="${product.title}" class="product-image">
        <div class="product-info">
            <h3 class="product-title">${product.title}</h3>
            <p class="product-price">JOD ${product.price}</p>
            <i class="favorite-icon" data-product-id="${product.id}">&#9825;</i> <!-- Empty heart icon -->
        </div>
    `;
    
        // Append the product card to the container
        productCardsContainer.appendChild(productCard);
    });

    // Add event listeners for the favorite icons
    setupFavoriteIconClickHandlers();
};

// Function to handle favorite icon click events
const setupFavoriteIconClickHandlers = () => {
    const favoriteIcons = document.querySelectorAll('.favorite-icon');
    favoriteIcons.forEach(icon => {
        icon.addEventListener('click', async (event) => {
            const productId = event.target.dataset.productId;

            try {
                const response = await callApi('addToWishlist', { product_id: productId });
                if (response.success) {
                    // Change the icon to a filled heart
                    event.target.innerHTML = '&#10084;'; // Filled heart icon
                    showUserMessage('Added to wishlist!', 'success');
                } else {
                    showUserMessage(response.message || 'Failed to add to wishlist.', 'error');
                }
            } catch (error) {
                console.error('Error adding to wishlist:', error);
                showUserMessage('An error occurred while adding to wishlist.', 'error');
            }
        });
    });
};

// Call the fetchProducts function when the page loads
window.addEventListener('DOMContentLoaded', fetchProducts);
