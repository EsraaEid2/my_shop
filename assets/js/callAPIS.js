// Dynamic base URL for API (switches between development and production)
const is_live = false; // Set to `true` for production
const base_url = is_live ? "https://my-shop.com" : "http://localhost/my_shop/api";

// API configurations
const apis = {
    "login": {
        "url": `${base_url}/login.php`,
        "method": "POST"
    },
    "profile": {
        "url": `${base_url}/get_user_data.php`,
        "method": "GET"
    },
    "updateProfile": {
        "url": `${base_url}/update_user_data.php`,
        "method": "POST"
    },
    "logout": {
        "url": `${base_url}/destroy_user_session.php`,
        "method": "GET"
    },
    "signUp": {
        "url": `${base_url}/sign_up.php`,
        "method": "POST"
    },
    "setUserSession": {
        "url": `${base_url}/set_user_session.php`,
        "method": "POST"
    },
    "getUserSession": {
        "url": `${base_url}/get_user_session.php`,
        "method": "GET"
    },
    "addProduct": {
        "url": `${base_url}/add_product.php`,
        "method": "POST"
    },
    "getProducts": {
        "url": `${base_url}/get_products.php`,
        "method": "GET"
    },
    "getProductById": {
        "url": `${base_url}/get_product_by_id.php`,
        "method": "GET"
    },
    "getUserProducts": {
        "url": `${base_url}/get_user_products.php`,
        "method": "GET"
    },
    "editUserProduct": {
        "url": `${base_url}/edit_user_product.php`,
        "method": "POST"
    },
    "deleteUserProduct": {
        "url": `${base_url}/delete_user_product.php`,
        "method": "POST"
    },
};

/* Custom logging Function
 logs messages during development
*/
function logMessage(message, level = "info") {
    const timestamp = new Date().toISOString();
    switch (level.toLowerCase()) {
        case "info":
            console.info(`[INFO] [${timestamp}] ${message}`);
            break;
        case "warn":
            console.warn(`[WARN] [${timestamp}] ${message}`);
            break;
        case "error":
            console.error(`[ERROR] [${timestamp}] ${message}`);
            break;
        default:
            console.log(`[LOG] [${timestamp}] ${message}`);
            break;
    }
}

// Define icons for each message type using more modern Material Symbols
const icons = {
    success: '<span class="material-symbols-rounded">check_circle</span>',
    error: '<span class="material-symbols-rounded">error</span>',
    warning: '<span class="material-symbols-rounded">warning</span>',
    info: '<span class="material-symbols-rounded">info</span>',
};

/**
 * Displays a user message as a toast notification
 * @param {string} message - The message to display
 * @param {string} type - Message type ("success", "error", "warning", "info")
 * @param {Object} customOptions - Custom options for the toast
 */
function showUserMessage(message, type = "info", customOptions = {}) {
    const defaultOptions = {
        duration: 3000,
        position: 'top', // 'top' or 'bottom'
        dismissible: true, // Allow manual dismissal
        progressBar: true,
    };

    const options = { ...defaultOptions, ...customOptions };

    // Validate type
    if (!Object.keys(icons).includes(type)) type = "info";

    // Remove existing message box
    const existingBox = document.getElementById("user-message-box");
    if (existingBox) existingBox.remove();

    // Create and display message box
    const messageBox = document.createElement("div");
    messageBox.id = "user-message-box";
    messageBox.className = `user-message ${type} ${options.position}`;

    // Add close button if dismissible
    const closeButton = options.dismissible ?
        '<button class="close-btn" onclick="this.parentElement.remove();">' +
        '<span class="material-symbols-rounded">close</span></button>' : '';

    messageBox.innerHTML = `
        <div class="message-content">
            ${icons[type]}
            <span class="message-text">${message}</span>
        </div>
        ${closeButton}`;

    // Add progress bar for auto-dismiss
    const progressBar = document.createElement("div");
    progressBar.className = "progress-bar";
    messageBox.appendChild(progressBar);

    // Append to body
    document.body.appendChild(messageBox);

    // Show message with animation
    requestAnimationFrame(() => {
        messageBox.classList.add("visible");
        progressBar.style.transition = `width ${options.duration}ms linear`;
        progressBar.style.width = "0%";
    });

    // Auto-dismiss after duration
    const timeout = setTimeout(() => {
        messageBox.classList.remove("visible");
        setTimeout(() => messageBox.remove(), 500);
    }, options.duration);

    // Clear timeout if manually dismissed
    if (options.dismissible) {
        messageBox.querySelector('.close-btn').addEventListener('click', () => {
            clearTimeout(timeout);
        });
    }
}

/**
 * Validates an image file based on size and type.
 * @param {File} file - The image file to validate.
 * @param {Object} options - Validation options.
 * @param {number} options.maxSizeMB - Maximum file size in MB.
 * @param {string[]} options.allowedTypes - Allowed MIME types.
 * @returns {Object} Validation result with success and message.
 */
const defaultValidationOptions = {
    maxSizeMB: 2,
    allowedTypes: ['image/jpeg', 'image/png'],
};

function validateImage(file, { maxSizeMB, allowedTypes }) {
    const maxSizeBytes = maxSizeMB * 1024 * 1024; // Convert MB to bytes
    const fileSize = file.size;

    if (fileSize > maxSizeBytes) {
        return { success: false, message: 'File is too large. Maximum size allowed is ' + maxSizeMB + 'MB.' };
    }

    const fileType = file.type;
    if (!allowedTypes.includes(fileType)) {
        return { success: false, message: `Invalid file type. Allowed types: ${allowedTypes.join(', ')}` };
    }

    return { success: true };
}

/**
 * Helper function to convert an image file to Base64 format.
 * @param {File} file - The file to convert.
 * @returns {Promise<string>} - A promise that resolves with the Base64 string.
 */
async function toBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result.split(",")[1]); // Get only the Base64 part
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

/**
 * Handles image upload, validates it, and processes it.
 * @param {Event} event - The input change event.
 */
async function handleImageUpload(event) {
    const file = event.target.files[0]; // Get the uploaded file

    // Validate the image
    const validation = validateImage(file, { maxSizeMB: 5, allowedTypes: ['image/jpeg', 'image/png', 'image/gif'] });

    if (!validation.success) {
        // Show an error message
        showUserMessage(validation.message, "error");
        event.target.value = ""; // Clear the file input
        return null; // Return null in case of validation failure
    }

    // Convert to Base64 and return it
    const imageBase64 = await toBase64(file);
    return imageBase64; // Return the base64 string
}

/**
 * Helper function to get a Base64 string of an image file from its path.
 * (This is a PHP function for server-side processing.)
 * @param {string} $imagePath - The path to the image file.
 * @returns {string} - Base64-encoded string.
 */
function getBase64Image($imagePath) {
    const $imageData = file_get_contents($imagePath);
    return base64_encode($imageData);
}

/**
* Makes an API call and handles responses
*/
async function callApi(apiName, data = null) {
    if (!apis[apiName]) {
        logMessage(`API '${apiName}' not found.`, "error");
        showUserMessage(`Error: API '${apiName}' not found.`, "error");
        return null;
    }

    const { url, method } = apis[apiName];
    let apiUrl = url;

    if (method === "GET" && data) {
        const queryParams = new URLSearchParams(data).toString();
        apiUrl += `?${queryParams}`;
    }

    logMessage(`Calling API '${apiName}' with URL: ${apiUrl} and data: ${JSON.stringify(data)}`);

    const options = {
        method: method,
        headers: {
            "Content-Type": "application/json",
        },
    };

    if (["POST", "PUT"].includes(method) && data) {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(apiUrl, options);
        const rawText = await response.text();

        logMessage(`Raw Response from '${apiName}': ${rawText}`);

        // Check if response starts with a string and JSON follows
        const jsonStart = rawText.indexOf("{");
        if (jsonStart === -1) {
            logMessage(`Invalid response from '${apiName}': ${rawText}`, "error");
            showUserMessage('Received an invalid response from the server.', "error");
            return null;
        }

        const jsonString = rawText.slice(jsonStart);
        const jsonData = JSON.parse(jsonString);

        if (response.ok) {
            showUserMessage(`API '${apiName}' executed successfully.`, "success");
            return jsonData;
        } else {
            const errorMsg = jsonData?.message || `Server returned status ${response.status}`;
            logMessage(`Error in API '${apiName}': ${errorMsg}`, "error");
            showUserMessage(errorMsg, "error");
            return null;
        }
    } catch (networkError) {
        logMessage(`Network/API Error for '${apiName}': ${networkError}`, "error");
        showUserMessage('Network issue or API is unavailable.', "error");
        return null;
    }
}



export { callApi, logMessage, showUserMessage, handleImageUpload ,validateImage};
