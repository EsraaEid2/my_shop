<?php 
$title = "Cake Shop | Products";
require_once('controls/header.php'); 
?>
<div class="products-container">
    <div class="container mt-5">
        <h2>Shop Products</h2>
        <div class="d-flex justify-content-end mt-4 mb-4">
    <button class="btn btn-primary btn-lg d-flex align-items-center gap-2" data-bs-toggle="modal"
        data-bs-target="#postProductModal">
        <i class="fas fa-plus-circle"></i> <span>Post Your Product</span>
    </button>
</div>
        <div class="product-cards-container">
            <?php
            // Assuming you have an array of products
            foreach ($products as $product) {
                echo '
                <div class="product-card">
                    <img src="' . $product['image_url'] . '" alt="' . $product['title'] . '" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title">' . $product['title'] . '</h3>
                        <p class="product-price">JOD' . $product['price'] . '</p>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>
</div>
<!-- Post Product Modal -->
<div class="modal fade" id="postProductModal" tabindex="-1" aria-labelledby="postProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="postProductModalLabel">Post Your Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id= "addProductForm">
                    <div class="mb-3">
                        <label for="title" class="form-label">Product Title</label>
                        <input type="text" name="title" class="form-control" id="title" placeholder="Enter product name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="description" placeholder="Enter product details" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price (JOD)</label>
                        <input type="number" name="price" class="form-control" id="price" placeholder="Enter product price" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="stock_quantity" class="form-label">Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="form-control" id="stock_quantity" placeholder="Enter available stock" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="image_url" class="form-label">Product Image</label>
                        <input type="file" name="image_url" class="form-control" id="image_url" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Post Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php 
require_once('controls/footer.php');
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/add_product.js" type="module"></script>
</body>

</html>