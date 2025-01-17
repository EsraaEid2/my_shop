import { callApi } from './config.js';

// Event delegation for delete functionality
document.addEventListener('click', async (event) => {
    if (event.target.classList.contains('btn-delete')) {
        const productId = event.target.getAttribute('data-id'); // Get the product ID
        if (productId) {
            await deleteProduct(productId); // Call the delete function
        }
    }
});

// Function to delete a product
async function deleteProduct(productId) {
    const userConfirmed = confirm('Are you sure you want to delete this product?');

    if (userConfirmed) {
        try {
            // Call the deleteProduct API
            const response = await callApi('deleteUserProduct', { id: productId });

            if (response && response.success) {
                // Display success message
                showUserMessage('Product deleted successfully.', 'success');

                // Remove the product from the DOM
                const productCard = document.getElementById(`product-${productId}`);
                if (productCard) {
                    productCard.remove();
                }
            } else {
                // Display error message
                showUserMessage(response.message || 'Failed to delete product.', 'error');
            }
        } catch (error) {
            // Handle unexpected errors
            console.error('Error deleting product:', error);
            showUserMessage(`An unexpected error occurred: ${error.message}`, 'error');
        }
    }
}

export { deleteProduct };
