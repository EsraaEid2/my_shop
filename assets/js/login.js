// Import the callApi function
import { callApi } from './config.js';

// Get references to the form
const form = document.getElementById('LoginForm');

// Add a submit event listener to the form
form.addEventListener('submit', async (e) => {
    e.preventDefault(); // Prevent the default form submission

    // Collect form data
    const formData = {
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
    };

    try {
        // Call the login API using callApi function
        const loginResponse = await callApi('login', formData);

        // Display response from login API in SweetAlert
        if (loginResponse.success) {
            const data = loginResponse.data;

            // Call the set_user_session API to set the session
            const sessionBody = { id: data.id };
            const sessionResponse = await callApi('set_user_session', sessionBody);

            // Handle session response
            if (sessionResponse.success) {
                // Success message
                Swal.fire({
                    icon: 'success',
                    title: 'Login Successful',
                    text: sessionResponse.message,
                }).then(() => {
                    // Redirect to profile page
                    window.location.href = '/my_shop/profile.php';
                });
            } else {
                // Handle session setting failure
                Swal.fire('Error', sessionResponse.message, 'error');
            }
        } else {
            // Handle login failure
            Swal.fire('Error', loginResponse.message, 'error');
        }
    } catch (error) {
        // Handle API errors
        console.log(error);
        Swal.fire('Error', `An error occurred: ${error.message}`, 'error');
    }
});
