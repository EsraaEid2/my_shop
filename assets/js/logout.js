import { callApi } from './config.js';

const logoutBtn = document.getElementById('logout-btn');

// Logout functionality
if (logoutBtn) {
    logoutBtn.addEventListener('click', async (e) => {
        e.preventDefault();

        try {
            const logoutResponse = await callApi('logout');
            if (logoutResponse.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Logged Out',
                    text: 'You have been logged out successfully.',
                }).then(() => {
                    window.location.href = '/my_shop/login.php';
                });
            } else {
                Swal.fire('Error', logoutResponse.message, 'error');
            }
        } catch (error) {
            Swal.fire('Error', `An error occurred: ${error.message}`, 'error');
        }
    });
}
