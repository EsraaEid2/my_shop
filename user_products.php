<?php 
session_start(); 
$title = "Cake Shop | Your Products";
require_once('controls/header.php'); 
?>
<div class="products-container">
    <div class="mt-5">
        <h2>Your Products</h2>
        <div class="d-flex justify-content-end mt-4 mb-4">
            <button class="btn btn-primary btn-lg d-flex align-items-center gap-2" data-bs-toggle="modal"
                data-bs-target="#postProductModal">
                <i class="fas fa-plus-circle"></i> <span>Post Your Product</span>
            </button>
        </div>
        <div class="product-cards-container" id="productCardsContainer">
            <!-- Product cards will be dynamically inserted here -->
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
                <form id="addProductForm" enctype="multipart/form-data">
                    <!-- Form fields for creating a new product -->
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

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Your Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    <input type="hidden" id="edit-product-id" name="product_id">
                    <div class="mb-3">
                        <label for="title" class="form-label">Product Title</label>
                        <input type="text" name="title" class="form-control" id="edit-title"
                            placeholder="Enter product name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="edit-description"
                            placeholder="Enter product details" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price (JOD)</label>
                        <input type="number" name="price" class="form-control" id="edit-price"
                            placeholder="Enter product price" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="stock_quantity" class="form-label">Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="form-control" id="edit-stock_quantity"
                            placeholder="Enter available stock" min="1" required>
                    </div>
                    <div class="mb-3 text-center">
                        <img id="edit-product-image" src="" alt="Product Image" class="img-fluid mb-3"
                            style="max-height: 200px;">
                    </div>
                    <div class="mb-3">
                        <label for="edit-image_url" class="form-label">Product Image</label>
                        <input type="file" name="image_url" class="form-control" id="edit-image_url">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Save Changes</button>
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
<script src="assets/js/delete_user_product.js" type="module"></script>
<script src="assets/js/display_products.js" type="module"></script>
<script src="assets/js/get_user_products.js" type="module"></script>
<script src="assets/js/add_product.js" type="module"></script>
<script src="assets/js/edit_user_product.js" type="module"></script>



</body>

</html>