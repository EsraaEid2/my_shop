import { callApi, showUserMessage } from './config.js';

// Event delegation for remove functionality
document.addEventListener('click', async (event) => {
    if (event.target.classList.contains('remove-icon')) {
        const productId = event.target.getAttribute('data-product-id'); // Get the product ID
        if (productId) {
            await removeFromWishlist(productId); // Call the remove function
        }
    }
});

// Function to remove a product from the wishlist
async function removeFromWishlist(productId) {
    const userConfirmed = confirm('Are you sure you want to remove this product from your wishlist?');

    if (userConfirmed) {
        try {
            // Call the removeFromWishlist API
            const response = await callApi('deleteFromWishlist', { product_id: productId });

            if (response && response.success) {
                // Display success message
                showUserMessage('Product removed from wishlist successfully.', 'success');

                // Remove the product from the DOM
                const productCard = document.querySelector(`[data-product-id="${productId}"]`).closest('.wishlist-item');
                if (productCard) {
                    productCard.remove();
                }
            } else {
                // Display error message
                showUserMessage(response.message || 'Failed to remove product from wishlist.', 'error');
            }
        } catch (error) {
            // Handle unexpected errors
            console.error('Error removing product from wishlist:', error);
            showUserMessage(`An unexpected error occurred: ${error.message}`, 'error');
        }
    }
}

export { removeFromWishlist };
