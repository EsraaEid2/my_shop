import { callApi,showUserMessage } from './config.js';
import { displayProducts } from './display_products.js';

// Function to fetch and display the user's products
const fetchUserProducts = async () => {
    try {
        const userSessionResponse = await callApi('getUserSession', null);

        if (userSessionResponse.success) {
            const userId = userSessionResponse.data.user_id;

            const productsResponse = await callApi('getUserProducts', { id: userId });

            if (productsResponse.success) {
                const products = productsResponse.data;

                const filteredProducts = products.filter(product => !product.is_deleted);
                const sortedProducts = filteredProducts.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

                displayProducts(sortedProducts);
            } else {
                showUserMessage(productsResponse.message || 'Failed to fetch products.', 'error');
            }
        } else {
            showUserMessage(userSessionResponse.message || 'Failed to fetch user session.', 'error');
        }
    } catch (error) {
        console.error('Error fetching user products:', error);
        showUserMessage('An error occurred while fetching user products.', 'error');
    }
};

window.addEventListener('DOMContentLoaded', fetchUserProducts);
