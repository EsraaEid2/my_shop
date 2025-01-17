// edit_user_product.js

import { callApi } from './config.js';

// Function to populate the edit form with product details
function populateEditProductForm(productId) {
    callApi('getProductById', { product_id: productId })
        .then(response => {
            if (response.success) {
                const product = response.data;

                document.getElementById('title').value = product.title;
                document.getElementById('description').value = product.description;
                document.getElementById('price').value = product.price;
                document.getElementById('stock_quantity').value = product.stock_quantity;

                document.getElementById('editProductForm').onsubmit = function(event) {
                    event.preventDefault();
                    editProductSubmit(productId);
                };
            } else {
                alert('Failed to fetch product details: ' + response.message);
            }
        })
        .catch(error => {
            console.error('Error fetching product details:', error);
            alert('An error occurred while fetching product details.');
        });
}

// Function to submit edited product data
function editProductSubmit(productId) {
    const title = document.getElementById('title').value;
    const description = document.getElementById('description').value;
    const price = document.getElementById('price').value;
    const stock_quantity = document.getElementById('stock_quantity').value;
    const image_url = document.getElementById('image_url').files[0];

    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('title', title);
    formData.append('description', description);
    formData.append('price', price);
    formData.append('stock_quantity', stock_quantity);
    if (image_url) {
        formData.append('image_url', image_url);
    }

    callApi('editUserProduct', formData).then(response => {
        if (response.success) {
            alert('Product updated successfully!');
            updateProductCard(productId, response.data.product);
            const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
            modal.hide();
        } else {
            alert('Error updating product: ' + response.message);
        }
    }).catch(error => {
        console.error('Error updating product:', error);
        alert('An error occurred while updating the product.');
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
