<?php 
session_start();
$title = "Cake Shop | Wishlist";
require_once('controls/header.php'); 
?>

<section class="wishlist">
    <div class="wishlist-container">
        <h2>Your Wishlist</h2>
        <div class="wishlist-items" id="wishlist-items">
            <!-- Example of recommended card structure -->

        </div>
    </div>
</section>

<?php 
require_once('controls/footer.php');
?>

<!-- Bootstrap 5 JS (No jQuery required) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap 5 -->

<!-- Your custom JS file -->
<script src="assets/js/get_user_wishlist.js" type="module"></script>
<script src= "assets/js/delete_from_wishlist.js" type = "module"></script>
</body>
</html>
