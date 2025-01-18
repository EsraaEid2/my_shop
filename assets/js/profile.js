import { handleImageUpload, showUserMessage, callApi, validateImage } from './config.js';

document.addEventListener('DOMContentLoaded', () => {
    const profileImageInput = document.getElementById('profileImageInput');
    const saveImageBtn = document.getElementById('saveImageBtn');
    const editImageIcon = document.getElementById('editImageIcon');
    const editProfileForm = document.getElementById('editProfileForm');

    const editFirstNameElem = document.getElementById('edit_first_name');
    const editLastNameElem = document.getElementById('edit_last_name');
    const editEmailElem = document.getElementById('edit_email');
    const userImage = document.getElementById('userImage');

    let userId = null;

    // Profile Image Edit Logic
    editImageIcon.addEventListener('click', () => profileImageInput.click());

    profileImageInput.addEventListener('change', async (event) => {
        const imageBase64 = await handleImageUpload(event);
        // console.log(imageBase64);
        
        if (imageBase64) {
            userImage.src = `data:image/jpeg;base64,${imageBase64}`;
            saveImageBtn.classList.remove('d-none');
        }
    });

    saveImageBtn.addEventListener('click', async () => {
        const fileInput = profileImageInput;  // Use the profileImageInput element directly
        if (await handleFileUpload(fileInput)) {
            saveImageBtn.classList.add('d-none');
        }
    });
    

    const handleFileUpload = async (fileInput) => {
        const file = fileInput.files[0];  // Get the file from the input
        if (!file) {
            showUserMessage('No Image Selected. Please select an image to upload!', 'error');
            return false;
        }
    
        const validation = validateImage(file, { maxSizeMB: 2, allowedTypes: ['image/jpeg', 'image/png'] });
        if (!validation.success) {
            showUserMessage(validation.message, 'error');
            return false;
        }
    
        const imageBase64 = await handleImageUpload(fileInput);  // Fix: Pass the event object (fileInput)
        if (!imageBase64) return false;
    
        const profileData = {
            user_id: userId,
            first_name: editFirstNameElem.value,
            last_name: editLastNameElem.value,
            email: editEmailElem.value,
            profile_image: imageBase64, // Send the base64 image
        };
    
        return await updateUserProfile(profileData);
    };
    
    
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
                    userImage.src = profile_image || 'assets/img/user_images/default_profile.png';
                }
            }
        } catch (error) {
            console.error('Error fetching user profile:', error);
        }
    };

    // Update Profile Logic
    const updateUserProfile = async (profileData) => {
        console.log(profileData);

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

        const profileData = {
            user_id: userId,
            first_name: editFirstNameElem.value,
            last_name: editLastNameElem.value,
            email: editEmailElem.value
        };

        await updateUserProfile(profileData);
    });

    // Email Validation
    function validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    // Initial fetch of user profile
    fetchUserProfile();
});
