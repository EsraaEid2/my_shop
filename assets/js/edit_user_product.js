import { callApi, handleImageUpload, showUserMessage } from './config.js';

document.addEventListener('DOMContentLoaded', () => {
    const editProductForm = document.getElementById('editProductForm');

    if (editProductForm) {
        editProductForm.addEventListener('submit', (event) => {
            event.preventDefault(); // Prevent default form submission behavior

            const productId = document.getElementById('edit-product-id').value; // Get product ID from hidden input field
            editProductSubmit(productId); // Call the function to handle API submission
        });
    }
});

/**
 * Populates the edit product form with product details and displays the modal.
 * @param {number} productId - The ID of the product to fetch details for.
 */
function populateEditProductForm(productId) {
    // Show the modal (Bootstrap)
    const editProductModal = new bootstrap.Modal(document.getElementById('editProductModal'));
    editProductModal.show();

    // Call the API to fetch product details
    callApi('getProductById', { product_id: productId })
        .then(response => {
            if (response.success) {
                const product = response.data;

                // Populate the form fields with the fetched product data
                document.getElementById('edit-product-id').value = productId;
                document.getElementById('edit-title').value = product.title;
                document.getElementById('edit-description').value = product.description;
                document.getElementById('edit-price').value = product.price;
                document.getElementById('edit-stock_quantity').value = product.stock_quantity;

                // Update the product image in the form
                const productImage = document.getElementById('edit-product-image');
                productImage.src = `http://localhost/my_shop/assets/img/product_images/${product.image_url}`;
                productImage.alt = product.title;
            } else {
                showUserMessage(response.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error fetching product details:', error);
            showUserMessage('An error occurred while fetching product details.', 'error');
        });
}

/**
 * Submits the edited product data to the API.
 * @param {number} productId - The ID of the product to update.
 */
async function editProductSubmit(productId) {
    const title = document.getElementById('edit-title').value;
    const description = document.getElementById('edit-description').value;
    const price = document.getElementById('edit-price').value;
    const stock_quantity = document.getElementById('edit-stock_quantity').value;
    const imageInput = document.getElementById('edit-image_url');
    const imageFile = imageInput.files[0];

    // Validate required fields
    if (!title || !description || !price || !stock_quantity) {
        Swal.fire('Error', 'All fields are required.', 'error');
        return;
    }

    // Handle image upload if a new image is provided
    let image_base64 = null;
    if (imageFile) {
        const base64Image = await handleImageUpload({ target: imageInput });
        if (!base64Image) {
            return; // Stop if image validation failed
        }
        image_base64 = base64Image;
    }

    // Prepare the payload for API submission
    const payload = {
        id: productId,
        title,
        description,
        price,
        stock_quantity,
        image_base64, // Add Base64 image if updated
    };

    // Call the API to update the product
    callApi('editUserProduct', payload)
        .then(response => {
            if (response.success) {
                showUserMessage('Product updated successfully!', 'success');
            } else {
                showUserMessage(`Error updating product: ${response.message}`, 'error');
            }
        })
        .catch(error => {
            console.error('Error updating product:', error);
            showUserMessage('An error occurred while updating the product.', 'error');
        });
}

// Attach functions to the global scope for access
window.populateEditProductForm = populateEditProductForm;
window.editProductSubmit = editProductSubmit;
