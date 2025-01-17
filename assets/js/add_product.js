// Import necessary functions from callAPIS.js
import { callApi, showUserMessage, handleImageUpload } from './config.js';

document.addEventListener('DOMContentLoaded', () => {
    // Reference the form
    const modal = document.getElementById('postProductModal');
    const form = document.getElementById('addProductForm');
    const imageInput = document.getElementById('image_url');

    // Event listener for when the modal is shown
    modal.addEventListener('shown.bs.modal', () => {
        modal.setAttribute('aria-hidden', 'false');
    });

    // Event listener for when the modal is hidden
    modal.addEventListener('hidden.bs.modal', () => {
        modal.setAttribute('aria-hidden', 'true');
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Collect form data
        const title = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();
        const price = parseFloat(document.getElementById('price').value);
        const stockQuantity = parseInt(document.getElementById('stock_quantity').value, 10);
        const imageFile = imageInput.files[0]; // Get the selected image file

        // Basic validation
        if (!title || !description || isNaN(price) || price <= 0 || isNaN(stockQuantity) || stockQuantity < 1 || !imageFile) {
            showUserMessage('Please fill in all fields with valid data.', 'error');
            return;
        }

        try {
            // Call the getUserSession API to retrieve user session
            const sessionResponse = await callApi('getUserSession');

            if (!sessionResponse || !sessionResponse.success) {
                showUserMessage('You must be logged in to add a product.', 'error');
                return;
            }

            const userId = sessionResponse.data.user_id; // Extract the user_id

            // Call the handleImageUpload function to handle the image
            const base64Image = await handleImageUpload({ target: { files: [imageFile] } });
            if (!base64Image) return;

            // Prepare the data object
            const formData = {
                title,
                description,
                price,
                stock_quantity: stockQuantity,
                image_url: base64Image, // Send the image in Base64 format
                user_id: userId // Add the user_id to the form data
            };

            // Call the API to add the product
            const addProductResponse = await callApi('addProduct', formData);

            // Handle API response
            if (addProductResponse && addProductResponse.success) {
                showUserMessage('Product added successfully!', 'success');
                form.reset(); // Reset the form after successful submission
            } else {
                showUserMessage(addProductResponse.message || 'Failed to add product.', 'error');
            }
        } catch (error) {
            // Handle unexpected errors
            console.error('Error adding product:', error);
            showUserMessage(`An unexpected error occurred: ${error.message}`, 'error');
        }
    });
});
