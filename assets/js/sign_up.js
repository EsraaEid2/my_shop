// Import the callApi function
import { callApi } from './config.js';
document.addEventListener('DOMContentLoaded', () => {
// Get references to the form
const form = document.getElementById('registrationForm');

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('cpassword').value;
    console.log("Form submitted with password mismatch");


    // Check if passwords match
    if (password !== confirmPassword) {
        // Display error in SweetAlert
        Swal.fire({
            icon: 'error',
            title: 'Passwords do not match',
            text: 'Please make sure both passwords are the same.',
        });
        return;
    }

    const formData = {
        first_name: document.getElementById('first_name').value,
        last_name: document.getElementById('last_name').value,
        email: document.getElementById('email').value,
        password: password,
    };

    try {
        // Call the sign_up API using callApi function
        const registrationResponse = await callApi('sign_up', formData);
        console.log('Registration response:', registrationResponse);
        if (registrationResponse && registrationResponse.success) {
            const data = registrationResponse.data;

            // Call the set_user_session API to set the session
            const sessionBody = { id: data.id };
            const sessionResponse = await callApi('set_user_session', sessionBody);
            console.log('Session response:', sessionResponse);
            if (sessionResponse && sessionResponse.success) {
                // Success message
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful',
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
            // Handle registration failure
            Swal.fire('Error', registrationResponse.message, 'error');
        }
    } catch (error) {
        // Handle API errors
        console.log(error);
        Swal.fire('Error', `An error occurred: ${error.message}`, 'error');
    }
});
});
