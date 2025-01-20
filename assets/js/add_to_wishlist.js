// Import necessary functions from config.js
import { callApi, showUserMessage } from './config.js';

document.addEventListener('DOMContentLoaded', () => {
    const wishlistIcons = document.querySelectorAll('.favorite-icon');

    wishlistIcons.forEach(icon => {
        icon.addEventListener('click', async (event) => {
            event.preventDefault();

            const productId = event.target.dataset.productId;
            console.log('[DEBUG] Clicked Product ID:', productId);

            if (!productId) {
                showUserMessage('Invalid product ID.', 'error');
                return;
            }

            try {
                // Step 1: Retrieve the user session
                const sessionResponse = await callApi('getUserSession');

                if (!sessionResponse || !sessionResponse.success) {
                    console.error('[DEBUG] Failed to retrieve user session:', sessionResponse);
                    showUserMessage('You must be logged in to add products to your wishlist.', 'error');
                    return;
                }

                const userId = sessionResponse.data?.user_id;
                console.log('[DEBUG] Retrieved User ID from Session:', userId);

                if (!userId) {
                    showUserMessage('Unable to retrieve user session. Please log in again.', 'error');
                    return;
                }

                // Step 2: Prepare data for addToWishlist API
                const formData = {
                    user_id: userId,
                    product_id: productId,
                };

                console.log('[DEBUG] Payload for addToWishlist:', formData);

                // Step 3: Call addToWishlist API
                const response = await callApi('addToWishlist', formData);

                if (response && response.success) {
                    event.target.innerHTML = '&#10084;'; // Filled heart icon
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
