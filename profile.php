<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | My Shop</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>

    <div class="container my-5">
        <h2 class="text-center mb-4">User Profile</h2>
        <div id="profile" class="card p-4 shadow-sm">
            <div class="form-group mb-3">
                <label for="first_name">First Name</label>
                <p id="first_name" class="font-weight-bold">John</p>
            </div>
            <div class="form-group mb-3">
                <label for="last_name">Last Name</label>
                <p id="last_name" class="font-weight-bold">Doe</p>
            </div>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <p id="email" class="font-weight-bold">john.doe@example.com</p>
            </div>
            <div class="d-flex justify-content-end">
                <button id="editProfileBtn" class="btn btn-warning">Edit Profile</button>
            </div>
        </div>

        <!-- Edit Form (hidden by default) -->
        <div id="editProfileForm" class="card p-4 shadow-sm mt-4" style="display: none;">
            <h3>Edit Profile</h3>
            <div class="form-group mb-3">
                <label for="edit_first_name">First Name</label>
                <input type="text" id="edit_first_name" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="edit_last_name">Last Name</label>
                <input type="text" id="edit_last_name" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="edit_email">Email</label>
                <input type="email" id="edit_email" class="form-control">
            </div>
            <div class="d-flex justify-content-between">
                <button id="saveProfileBtn" class="btn btn-success">Save Changes</button>
                <button id="cancelEditBtn" class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/profile.js" type="module"></script>
</body>

</html>
