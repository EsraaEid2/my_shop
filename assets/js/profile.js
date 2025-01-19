import { handleImageUpload, showUserMessage, callApi } from './config.js';

document.addEventListener('DOMContentLoaded', () => {
    const profileImageInput = document.getElementById('profileImageInput');
    const editImageIcon = document.getElementById('editImageIcon');
    const editProfileForm = document.getElementById('editProfileForm');

    const editFirstNameElem = document.getElementById('edit_first_name');
    const editLastNameElem = document.getElementById('edit_last_name');
    const editEmailElem = document.getElementById('edit_email');
    const userImage = document.getElementById('userImage');

    let userId = null;
    let selectedImageBase64 = null;

    // Profile Image Edit Logic
    editImageIcon.addEventListener('click', () => profileImageInput.click());

    profileImageInput.addEventListener('change', async (event) => {
        const imageFile = profileImageInput.files[0];

        if (!imageFile) {
            showUserMessage('Please select an image.', 'error');
            return;
        }

        // Convert the image to Base64
        const imageBase64 = await handleImageUpload(event);

        if (imageBase64) {
            selectedImageBase64 = imageBase64; // Store the image in Base64 format
            userImage.src = `data:image/jpeg;base64,${imageBase64}`;
        }
    });

    // Update Profile Logic
    const updateProfile = async (profileData) => {
        try {
            const response = await callApi('updateProfile', profileData);

            if (response.success) {
                showUserMessage('Your profile information has been updated.', 'success');
                return true;
            } else {
                showUserMessage(response.message || 'Failed to update profile.', 'error');
                return false;
            }
        } catch (error) {
            console.error('Error updating profile:', error);
            showUserMessage('An error occurred while updating your profile.', 'error');
            return false;
        }
    };

    // Profile Form Submit Logic
    editProfileForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Validate email format
        if (!validateEmail(editEmailElem.value)) {
            showUserMessage('Please enter a valid email address.', 'error');
            return;
        }

        // Prepare profile data
        const profileData = {
            user_id: userId,
            first_name: editFirstNameElem.value,
            last_name: editLastNameElem.value,
            email: editEmailElem.value,
        };

        // Include the selected image if available
        if (selectedImageBase64) {
            profileData.profile_image = selectedImageBase64;
        }

        // Update the user profile
        const success = await updateProfile(profileData);
        if (success) {
            selectedImageBase64 = null; // Clear the image data after successful update
        }
    });

    // Fetch User Profile Logic
    const fetchUserProfile = async () => {
        try {
            const sessionResponse = await callApi('getUserSession', null);
            if (sessionResponse.success) {
                userId = sessionResponse.data.user_id;

                const profileResponse = await callApi('profile', { id: userId });
                if (profileResponse.success) {
                    const { first_name, last_name, email, profile_image } = profileResponse.data;

                    // Populate the profile form
                    editFirstNameElem.value = first_name || '';
                    editLastNameElem.value = last_name || '';
                    editEmailElem.value = email || '';

                    document.getElementById('userName').textContent = `${first_name || 'N/A'} ${last_name || ''}`.trim();
                    document.getElementById('userEmail').textContent = email || 'N/A';
                    console.log('Profile Image URL:', profile_image);

                    // Set the image source to the profile image URL or default image if none exists
                    userImage.src = profile_image ? profile_image : 'assets/img/user_images/default_profile.png';
                }
            }
        } catch (error) {
            console.error('Error fetching user profile:', error);
        }
    };

    // Email Validation
    function validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    // Initial fetch of user profile
    fetchUserProfile();
});
