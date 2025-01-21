import { callApi, showUserMessage } from './config.js';

// Function to fetch and display the user's products
const fetchUserProducts = async () => {
    try {
        console.log('Fetching user session...');
        const userSessionResponse = await callApi('getUserSession', null);

        if (userSessionResponse.success) {
            const userId = userSessionResponse.data.user_id;
            console.log(`User ID retrieved: ${userId}`);

            // Ensure the userId is valid before making the next request
            if (!userId || isNaN(userId)) {
                console.error('Invalid user ID');
                showUserMessage('Invalid user session.', 'error');
                return;
            }

            console.log('Fetching user wishlist...');
            const productsResponse = await callApi('getUserWishlist', { id: userId });

            if (productsResponse.success) {
                const products = productsResponse.data;

                console.log('Filtering and sorting products...');
                const filteredProducts = products.filter(product => !product.is_deleted);
                const sortedProducts = filteredProducts.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

                displayProducts(sortedProducts);
            } else {
                console.error('Failed to fetch products:', productsResponse.message);
                showUserMessage(productsResponse.message || 'Failed to fetch products.', 'error');
            }
        } else {
            console.error('Failed to fetch user session:', userSessionResponse.message);
            showUserMessage(userSessionResponse.message || 'Failed to fetch user session.', 'error');
        }
    } catch (error) {
        console.error('Error fetching user products:', error);
        showUserMessage('An error occurred while fetching user products.', 'error');
    }
};

// Function to display the products dynamically
const displayProducts = (products) => {
    const productsContainer = document.querySelector('.wishlist-items');
    productsContainer.innerHTML = ''; // Clear existing content

    products.forEach(product => {
        const productCard = document.createElement('article');
        productCard.classList.add('wishlist-item');

        productCard.innerHTML = `
        <img src="${product.image_url}" alt="${product.title}" class="product-image" loading="lazy">
        <div class="product-info">
            <h3 class="product-title">${product.title}</h3>
            <p class="product-price">JOD ${product.price}</p>
            <i class="remove-icon" data-product-id="${product.id}">&#128465;</i> <!-- Trash can icon for removal -->
        </div>
    `;

        // Append product card to container
        productsContainer.appendChild(productCard);

        // Add event listener for remove icon
        const removeIcon = productCard.querySelector('.remove-icon');
        removeIcon.addEventListener('click', () => removeFromWishlist(product.id));
    });
};

// Function to remove product from wishlist
const removeFromWishlist = async (productId) => {
    try {
        console.log('Fetching user session for removal...');
        const userSessionResponse = await callApi('getUserSession', null);

        if (userSessionResponse.success) {
            const userId = userSessionResponse.data.user_id;
            console.log(`User ID for removal: ${userId}`);

            // Ensure the userId and productId are valid before proceeding
            if (!userId || !productId || isNaN(userId) || isNaN(productId)) {
                console.error('Invalid user ID or product ID');
                showUserMessage('Invalid user or product ID.', 'error');
                return;
            }

            console.log('Calling API to remove product from wishlist...');
            const removeResponse = await callApi('deleteFromWishlist', { user_id: userId, product_id: productId });

            if (removeResponse.success) {
                showUserMessage('Product removed from wishlist.', 'success');
                fetchUserProducts(); // Refresh the wishlist after removal
            } else {
                console.error('Failed to remove product:', removeResponse.message);
                showUserMessage(removeResponse.message || 'Failed to remove product.', 'error');
            }
        } else {
            console.error('Failed to fetch user session:', userSessionResponse.message);
            showUserMessage(userSessionResponse.message || 'Failed to fetch user session.', 'error');
        }
    } catch (error) {
        console.error('Error removing product:', error);
        showUserMessage('An error occurred while removing the product.', 'error');
    }
};

// Call fetchUserProducts when the page loads
window.addEventListener('DOMContentLoaded', fetchUserProducts);
