// Import the necessary functions
import { logMessage, callApi, showUserMessage } from './config.js';

const logoutBtn = document.getElementById('logout-button');

// Logout functionality
if (logoutBtn) {
    logoutBtn.addEventListener('click', async (e) => {
        e.preventDefault();

        try {
            const logoutResponse = await callApi('logout');
            if (logoutResponse.success) {
                // Corrected call to showUserMessage
                showUserMessage('You have been logged out successfully.', 'success');
                // Redirect after message is displayed
                setTimeout(() => {
                    window.location.href = '/my_shop/login.php';
                }, 3000); // Wait for 3 seconds before redirect
            } else {
                // Display error message if logout fails
                showUserMessage(logoutResponse.message, 'error');
            }
        } catch (error) {
            // Handle any network or API error
            showUserMessage(`An error occurred: ${error.message}`, 'error');
        }
    });
}
