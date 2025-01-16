// Import the necessary functions
import { callApi, showUserMessage } from './config.js';

document.addEventListener('DOMContentLoaded', () => {
    // Get references to the form
    const form = document.getElementById('registrationForm');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('cpassword').value;

        // Check if passwords match
        if (password !== confirmPassword) {
            showUserMessage('Passwords do not match','error');
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
                    showUserMessage('Registration Successful','success');
                    setTimeout(() => {
                        // Redirect to profile page after the toast disappears
                        window.location.href = '/my_shop/shop.php';
                    }, 3100);
                } else {
                    // Handle session setting failure
                    showUserMessage(sessionResponse.message,'error');
                }
            } else {
                // Handle registration failure
                showUserMessage(registrationResponse.message,'error');
            }
        } catch (error) {
            // Handle API errors
            console.log(error);
            showUserMessage(`An error occurred: ${error.message}`,'error');
        }
    });
});
