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
    "uploadProfileImage": { 
        "url": `${base_url}/upload_profile_image.php`, 
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
    "getUserProducts": { 
        "url": `${base_url}/get_user_products.php`, 
        "method": "GET" 
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

    // Create message box
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

// Helper function to convert image to base64
function getBase64Image($imagePath) {
    $imageData = file_get_contents($imagePath);
    return base64_encode($imageData);
}

// Convert a file to Base64
async function toBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result.split(",")[1]);
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

// Function to make an API call
async function callApi(apiName, data = null) {
    // Validate API configuration
    if (!apis[apiName]) {
        logMessage(`API '${apiName}' not found.`, "error");
        showUserMessage(`Error: API '${apiName}' not found.`, "error");
        return null;
    }

    const { url, method } = apis[apiName];
    let apiUrl = url;

    // Append query parameters for GET requests
    if (method === "GET" && data) {
        const queryParams = new URLSearchParams(data).toString();
        apiUrl += `?${queryParams}`;
    }

    // Request options
    const options = {
        method: method,
        headers: {
            "Content-Type": "application/json",
        },
    };

    // Include request body for POST/PUT methods
    if (["POST", "PUT"].includes(method) && data) {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(apiUrl, options);
        const rawText = await response.text();

        logMessage(`Raw Response from '${apiName}': ${rawText}`);

        const jsonData = JSON.parse(rawText);

        if (response.ok) {
            showUserMessage(`API '${apiName}' executed successfully.`, "success");
            return jsonData;
        } else {
            logMessage(`Error in API '${apiName}': ${jsonData.message || 'Unknown error'}`, "error");
            showUserMessage(jsonData.message || 'An error occurred.', "error");
            return null;
        }
    } catch (error) {
        logMessage(`Network/API Error for '${apiName}': ${error}`, "error");
        showUserMessage('Network issue or API is unavailable.', "error");
        return null;
    }
}

export { callApi, logMessage, showUserMessage, getBase64Image, toBase64 };
