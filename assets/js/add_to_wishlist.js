// Assuming `userId` is available globally (from the inline PHP script above)

document.addEventListener('DOMContentLoaded', () => {
    const wishlistIcons = document.querySelectorAll('.favorite-icon');

    wishlistIcons.forEach(icon => {
        icon.addEventListener('click', async (event) => {
            event.preventDefault();

            const productId = event.target.dataset.productId; // Get product ID from the data attribute
            console.log('[DEBUG] Clicked Product ID:', productId);

            if (!productId) {
                showUserMessage('Invalid product ID.', 'error');
                return;
            }

            if (!userId) {
                showUserMessage('User must be logged in to add to wishlist.', 'error');
                return;
            }

            try {
                // Prepare the payload (including product_id and user_id)
                const formData = {
                    product_id: productId,
                    user_id: userId,  // Include the user_id from the session
                };

                console.log('[DEBUG] Payload for addToWishlist:', formData);

                // Call addToWishlist API
                const response = await callApi('addToWishlist', formData);

                if (response && response.success) {
                    event.target.innerHTML = '&#10084;'; // Update the icon to a filled heart
                    showUserMessage('Product added to wishlist!', 'success');
                } else {
                    console.error('[DEBUG] Failed to add to wishlist:', response);
                    showUserMessage(response.message || 'Failed to add product to wishlist.', 'error');
                }
            } catch (error) {
                console.error('[DEBUG] Unexpected error:', error);
                showUserMessage(`An unexpected error occurred: ${error.message}`, 'error');
            }
        });
    });
});
