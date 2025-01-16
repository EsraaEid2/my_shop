import { logMessage, callApi, showUserMessage } from './config.js';

document.addEventListener('DOMContentLoaded', () => {
    const profileImageInput = document.getElementById('profileImageInput');
    const saveImageBtn = document.getElementById('saveImageBtn');
    const editImageIcon = document.getElementById('editImageIcon');
    const editProfileForm = document.getElementById('editProfileForm');
    const tabs = document.querySelectorAll('.nav-link');
    let userId = null;

    // Initialize Bootstrap tabs
    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const bootstrapTabHandler = new bootstrap.Tab(this);
            bootstrapTabHandler.show();
        });
    });

    const editFirstNameElem = document.getElementById('edit_first_name');
    const editLastNameElem = document.getElementById('edit_last_name');
    const editEmailElem = document.getElementById('edit_email');
    const userImage = document.getElementById('userImage');

    editImageIcon.addEventListener('click', () => profileImageInput.click());

    profileImageInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                userImage.src = e.target.result;
                saveImageBtn.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    saveImageBtn.addEventListener('click', async () => {
        const fileInput = profileImageInput.files[0];
        if (await handleFileUpload(fileInput)) {
            saveImageBtn.classList.add('d-none');
        }
    });

    const handleFileUpload = async (fileInput) => {
        if (!fileInput) {
            showUserMessage( 'No Image Selected ,Please select an image to upload!','error');
            return false;
        }

        const allowedExtensions = ['image/jpeg', 'image/png'];
        const maxSize = 2 * 1024 * 1024;

        if (!allowedExtensions.includes(fileInput.type)) {
            showUserMessage('Invalid Image Format, Allowed formats: JPEG, PNG.','error');
            return false;
        }

        if (fileInput.size > maxSize) {
            showUserMessage('File Too Large, Maximum allowed size is 2MB.','error');
            return false;
        }

        const formData = new FormData();
        formData.append('profile_image', fileInput);

        try {
            const response = await callApi('uploadProfileImage', formData);
            if (response.success) {
                showUserMessage('Your profile image has been updated successfully.','success');
                return true;
            } else {
                showUserMessage(response.message || 'An error occurred during upload.','error');
                return false;
            }
        } catch (error) {
            console.error('Upload Error:', error);
            showUserMessage('An error occurred while uploading the image.','error');
            return false;
        }
    };

    const fetchUserProfile = async () => {
        try {
            const sessionResponse = await callApi('getUserSession', null);
            if (sessionResponse.success) {
                userId = sessionResponse.data.user_id;

                const profileResponse = await callApi('profile', { id: userId });
                if (profileResponse.success) {
                    const { first_name, last_name, email, profile_image } = profileResponse.data;

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

    fetchUserProfile();

    editProfileForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!validateEmail(editEmailElem.value)) {
            showUserMessage('Please enter a valid email address.','error');
            return;
        }

        const profileData = {
            user_id: userId,
            first_name: editFirstNameElem.value,
            last_name: editLastNameElem.value,
            email: editEmailElem.value
        };

        try {
            const response = await callApi('updateProfile', profileData);

            if (response.success) {
                showUserMessage('Your profile information has been updated.','success');
            } else {
                showUserMessage( response.message || 'Failed to update profile.','error');
            }
        } catch (error) {
            console.error('Error updating profile:', error);
            showUserMessage('An error occurred while updating your profile.','error');
        }
    });

    function validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
});
