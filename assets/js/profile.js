// Import the callApi function
import { callApi } from './config.js';

let userId = null; // We'll fetch the user ID from the session data

// DOM Elements
const profileDiv = document.getElementById('profile');
const editProfileDiv = document.getElementById('editProfileForm');
const editProfileBtn = document.getElementById('editProfileBtn');
const saveProfileBtn = document.getElementById('saveProfileBtn');

const firstNameElem = document.getElementById('first_name');
const lastNameElem = document.getElementById('last_name');
const emailElem = document.getElementById('email');

// Edit fields
const editFirstNameElem = document.getElementById('edit_first_name');
const editLastNameElem = document.getElementById('edit_last_name');
const editEmailElem = document.getElementById('edit_email');

// Fetch user session and data on page load
window.addEventListener('load', async () => {
    try {
        // Call the get user session API using callApi function
        const sessionResponse = await callApi('get_user_session', null);
        if (sessionResponse) {
            console.log('Session API Response:', sessionResponse);
            const sessionResult = sessionResponse;

            if (sessionResult.success) {
                // Get user ID from the session
                userId = sessionResult.data.user_id;
                console.log(`User ID from session: ${userId}`);

                // Fetch user data using userId
                const profileResponse = await callApi('profile', { id: userId });

           
                const profileResult = profileResponse;
                console.log(profileResult);
                
                if (profileResult.success) {
                    const user = profileResult.data;

                    // Populate profile fields
                    firstNameElem.textContent = user.first_name || "N/A";
                    lastNameElem.textContent = user.last_name || "N/A";
                    emailElem.textContent = user.email || "N/A";
                } else {
                    alert(profileResult.message);
                }
            } else {
                alert(sessionResult.message); // No valid session
                window.location.href = '/login.php'; // Redirect to login
            }
        }
    } catch (error) {
        console.error('Error fetching profile or session:', error);
        alert('Error fetching profile or session. Please try again.');
    }
});


// Edit profile button functionality
editProfileBtn.addEventListener('click', () => {
    // Show edit form and hide profile details
    profileDiv.style.display = 'none';
    editProfileDiv.style.display = 'block';

    // Prefill the edit form with current data
    editFirstNameElem.value = firstNameElem.textContent.trim();
    editLastNameElem.value = lastNameElem.textContent.trim();
    editEmailElem.value = emailElem.textContent.trim();
});

// Save the changes to the profile
saveProfileBtn.addEventListener('click', async () => {
    if (!userId) {
        Swal.fire('Error', 'User session is invalid. Please log in again.', 'error');
        return;
    }
    const updatedData = {
        first_name: editFirstNameElem.value.trim(),
        last_name: editLastNameElem.value.trim(),
        email: editEmailElem.value.trim()
    };

    if (!updatedData.first_name || !updatedData.last_name || !updatedData.email) {
        Swal.fire('Error', 'All fields are required.', 'error');
        return;
    }

    // Email validation
    if (!validateEmail(updatedData.email)) {
        Swal.fire('Error', 'Please enter a valid email address.', 'error');
        return;
    }
    try {
        const result = await callApi('edit_profile', { id: userId, ...updatedData });

        if (result && result.success) {
            // Update UI with new data
            firstNameElem.textContent = updatedData.first_name;
            lastNameElem.textContent = updatedData.last_name;
            emailElem.textContent = updatedData.email;

            profileDiv.style.display = 'block';
            editProfileDiv.style.display = 'none';

            Swal.fire({
                icon: 'success',
                title: 'Profile Updated',
                text: result.message,
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: result.message,
            });
        }
    } catch (error) {
        console.error('Error updating profile:', error);
        Swal.fire('Error', 'Error updating profile. Please try again.', 'error');
    }
});


function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}
