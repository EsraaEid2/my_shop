// Import the necessary functions
import { logMessage, callApi, showUserMessage } from './config.js';

// Get references to the form
const form = document.getElementById('LoginForm');

// Add a submit event listener to the form
form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = {
        email: document.getElementById('email').value.trim(),
        password: document.getElementById('password').value.trim(),
    };

    // Basic validation
    if (!formData.email || !formData.password) {
        showUserMessage('Email and Password are required!', 'warning');
        return;
    }

    const submitButton = form.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.textContent = 'Logging in...';

    try {
        // Call the login API
        const loginResponse = await callApi('login', formData);

        if (loginResponse.success) {
            const { id } = loginResponse.data;
            showUserMessage('Login successful! Redirecting...', 'success');

            // Set the user session
            const sessionResponse = await callApi('setUserSession', { id });

            if (sessionResponse && sessionResponse.success) {
                // Redirect to the user profile page after a slight delay
                setTimeout(() => {
                    window.location.href = '/my_shop/profile.php';
                }, 2000);
            } else {
                showUserMessage('Failed to set session. Please try again.', 'error');
            }
        } else {
            // Invalid credentials or any other failure in the login process
            showUserMessage(loginResponse.message || 'Invalid credentials.', 'error');
        }
    } catch (error) {
        console.error('Login error:', error);
        showUserMessage('An error occurred. Please try again later.', 'error');
    } finally {
        // Enable the submit button and reset the text
        submitButton.disabled = false;
        submitButton.textContent = 'Login';
    }
});
