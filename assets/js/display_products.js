// Function to render the products in the DOM
export const displayProducts = (products) => {
    const productCardsContainer = document.querySelector('.product-cards-container');
    productCardsContainer.innerHTML = ''; // Clear existing products

    products.forEach(product => {
        const productCard = document.createElement('div');
        productCard.classList.add('product-card');

        productCard.innerHTML = `
            <img src="${product.image_url}" alt="${product.title}" class="product-image">
            <div class="product-info">
                <h3 class="product-title">${product.title}</h3>
                <p class="product-price">JOD ${product.price}</p>
                <button class="btn-edit text-primary" data-bs-toggle="modal" data-bs-target="#editProductModal" onclick="populateEditProductForm(${product.id})">Edit</button>
                <button class="btn-delete text-danger" data-id="${product.id}">Delete</button>
            </div>
        `;

        productCard.id = `product-${product.id}`;
        productCardsContainer.appendChild(productCard);
    });
};
