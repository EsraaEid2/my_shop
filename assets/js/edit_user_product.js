import { callApi, toBase64 , showUserMessage } from './config.js';

function populateEditProductForm(productId) {
    console.log(productId);

    callApi('getProductById', { product_id: productId })
        .then(response => {
            if (response.success) {
                const product = response.data;

                // Update the modal fields
                document.getElementById('edit-product-id').value = productId; // Set hidden input
                document.getElementById('edit-title').value = product.title;
                document.getElementById('edit-description').value = product.description;
                document.getElementById('edit-price').value = product.price;
                document.getElementById('edit-stock_quantity').value = product.stock_quantity;

                // Set the product image with the correct path
                const productImage = document.getElementById('edit-product-image');
                productImage.src = `http://localhost/my_shop/assets/img/product_images/${product.image_url}`;
                productImage.alt = product.title;
            } else {
                Swal.fire('Error', 'Failed to fetch product details: ' + response.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error fetching product details:', error);
            Swal.fire('Error', 'An error occurred while fetching product details.', 'error');
        });
}


// Function to submit edited product data
async function editProductSubmit(productId) {
    const title = document.getElementById('edit-title').value;
    const description = document.getElementById('edit-description').value;
    const price = document.getElementById('edit-price').value;
    const stock_quantity = document.getElementById('edit-stock_quantity').value;
    const image_file = document.getElementById('edit-image_url').files[0];

    if (!title || !description || !price || !stock_quantity) {
        Swal.fire('Error', 'All fields are required.', 'error');
        return;
    }

    let image_base64 = null;
    if (image_file) {
        if (image_file.size > 2 * 1024 * 1024) { // 2MB max size
            Swal.fire('Error', 'The file size exceeds 2MB.', 'error');
            return;
        }
        image_base64 = await toBase64(image_file);
    }

    const payload = {
        id: productId,
        title,
        description,
        price,
        stock_quantity,
        image_base64,
    };

    callApi('editUserProduct', payload)
        .then(response => {
            if (response.success) {
                Swal.fire('Success', 'Product updated successfully!', 'success');
                updateProductCard(productId, response.data);
            } else {
                Swal.fire('Error', 'Error updating product: ' + response.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error updating product:', error);
            Swal.fire('Error', 'An error occurred while updating the product.', 'error');
        });
}




// Function to update the product card after editing
function updateProductCard(productId, updatedProduct) {
    const productCard = document.querySelector(`#product-${productId}`);

    productCard.innerHTML = `
        <img src="${updatedProduct.image_url}" alt="${updatedProduct.title}" class="product-image">
        <div class="product-info">
            <h3 class="product-title">${updatedProduct.title}</h3>
            <p class="product-price">JOD ${updatedProduct.price}</p>
            <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editProductModal" onclick="populateEditProductForm(${updatedProduct.id})">Edit</button>
            <button class="btn-delete text-danger" onclick="deleteProduct(${updatedProduct.id})">Delete</button>
        </div>
    `;
}

// Attach to the global scope for access
window.populateEditProductForm = populateEditProductForm;
window.editProductSubmit = editProductSubmit;
