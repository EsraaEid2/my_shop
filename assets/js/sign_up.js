// Import the necessary functions
import { logMessage, callApi, showUserMessage } from './config.js';

document.addEventListener('DOMContentLoaded', () => {
    // Get references to the form
    const form = document.getElementById('registrationForm');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('cpassword').value;

        // Check if passwords match
        if (password !== confirmPassword) {
            showUserMessage(
                'error',  // Type of message
                'Passwords do not match',  // Main message
                'Please make sure both passwords are the same.'  // Detailed message
            );
            return;
        }

        const formData = {
            first_name: document.getElementById('first_name').value,
            last_name: document.getElementById('last_name').value,
            email: document.getElementById('email').value,
            password: password,
        };

        try {
            // Call the signUp API using callApi function
            const registrationResponse = await callApi('signUp', formData);
            console.log('Registration response:', registrationResponse);
            if (registrationResponse && registrationResponse.success) {
                const data = registrationResponse.data;

                // Call the setUserSession API to set the session
                const sessionBody = { id: data.id };
                const sessionResponse = await callApi('setUserSession', sessionBody);
                console.log('Session response:', sessionResponse);
                if (sessionResponse && sessionResponse.success) {
                    // Success message
                    showUserMessage(
                        'success',  // Type of message
                        'Registration Successful',  // Main message
                        sessionResponse.message,  // Detailed message
                        { toast: true, position: 'bottom-end', timer: 3000, timerProgressBar: true }  // Custom options
                    );
                    setTimeout(() => {
                        // Redirect to profile page after the toast disappears
                        window.location.href = '/my_shop/profile.php';
                    }, 3100);
                } else {
                    // Handle session setting failure
                    showUserMessage('error', 'Error', sessionResponse.message);
                }
            } else {
                // Handle registration failure
                showUserMessage('error', 'Error', registrationResponse.message);
            }
        } catch (error) {
            // Handle API errors
            console.log(error);
            showUserMessage('error', 'Error', `An error occurred: ${error.message}`);
        }
    });
});
