<?php
session_start(); 
$title = "Cake Shop | My Profile";
require_once('controls/header.php'); 
?>

<div class="container my-5">
    <h2 class="text-center mb-4">User Profile</h2>

    <!-- Profile Section -->
    <div id="profile" class="card p-4 shadow-sm">
        <div class="d-flex align-items-center">
            <!-- User Image & Edit Icon -->
            <div class="position-relative">
                <img src="assets/img/user_images/default_profile.png" alt="User Image" id="userImage"
                     class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                <div id="editImageIcon" class="position-absolute top-0 end-0 p-2 bg-white rounded-circle"
                     style="cursor: pointer;">
                    <i class="fas fa-edit"></i>
                </div>
                <input type="file" id="profileImageInput" class="d-none" accept="image/*">
            </div>

            <!-- User Name -->
            <div class="ms-4">
                <h3 id="userName">John Doe</h3>
                <p id="userEmail" class="text-muted">john.doe@example.com</p>
            </div>
        </div>
    </div>

    <!-- Save Image Button -->
    <div class="text-center mt-3">
        <button id="saveImageBtn" class="btn btn-success d-none">Save Image</button>
    </div>

    <!-- Edit Info Section -->
    <div class="mt-5">
        <h4 class="mb-4">Edit Your Information</h4>
        <form id="editProfileForm">
            <div class="form-group mb-3">
                <label for="edit_first_name">First Name</label>
                <input type="text" id="edit_first_name" class="form-control" value="John">
            </div>
            <div class="form-group mb-3">
                <label for="edit_last_name">Last Name</label>
                <input type="text" id="edit_last_name" class="form-control" value="Doe">
            </div>
            <div class="form-group mb-3">
                <label for="edit_email">Email</label>
                <input type="email" id="edit_email" class="form-control" value="john.doe@example.com">
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>

<?php 
require_once('controls/footer.php');
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/profile.js" type="module"></script>

</body>
</html>
