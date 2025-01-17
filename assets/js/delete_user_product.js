import { callApi } from './config.js';

// Function to delete the product
function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        callApi('deleteProduct', { product_id: productId }).then(response => {
            if (response.success) {
                alert('Product deleted successfully!');
                document.querySelector(`#product-${productId}`).remove();
            } else {
                alert('Error deleting product: ' + response.message);
            }
        }).catch(error => {
            console.error('Error deleting product:', error);
            alert('An error occurred while deleting the product.');
        });
    }
}

// Attach to the global scope for access
window.deleteProduct = deleteProduct;
