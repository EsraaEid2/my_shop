// Import the necessary functions
import { logMessage, callApi, showUserMessage } from './config.js';

const logoutBtn = document.getElementById('logout-btn');

// Logout functionality
if (logoutBtn) {
    logoutBtn.addEventListener('click', async (e) => {
        e.preventDefault();

        try {
            const logoutResponse = await callApi('logout');
            if (logoutResponse.success) {
                showUserMessage('success', 'Logged Out', 'You have been logged out successfully.', {
                    toast: true,
                    position: 'bottom-end',
                    timer: 3000,
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = '/my_shop/login.php';
                });
            } else {
                showUserMessage('error', 'Error', logoutResponse.message);
            }
        } catch (error) {
            showUserMessage('error', 'Error', `An error occurred: ${error.message}`);
        }
    });
}
