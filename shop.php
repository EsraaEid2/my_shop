<?php 
session_start(); 
$title = "Cake Shop | Shop";
require_once('controls/header.php'); 
?>
<div class="products-container">
    <div class=" mt-5">
        <h2>Shop Products</h2>
        <div class="product-cards-container" id="productCardsContainer">
            <!-- Product cards will be dynamically inserted here -->
        </div>
    </div>
</div>

<?php 
require_once('controls/footer.php');
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/get_products.js" type="module"></script>
<script src="assets/js/add_to_wishlist.js" type="module"></script>

</body>
</html>
