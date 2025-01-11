// Dynamic base URL for API (switches between development and production)
const is_live = false; // Set to `true` for production
const base_url = is_live ? "https://my-shop.com" : "http://localhost/my_shop";

// API configurations
const apis = {
    login: { url: "/api/login.php", method: "POST" },
    profile: { url: "/api/get_user_data.php", method: "GET" },
    edit_profile: {url: "/api/update_user_data.php", method: "POST"},
    logout: { url: "/api/destroy_user_session.php", method: "GET" },
    sign_up: { url: "/api/sign_up.php", method: "POST" },
    set_user_session: { url: "/api/set_user_session.php", method: "POST" },
    get_user_session: { url: "/api/get_user_session.php", method: "GET" },
};

// Function to make an API call
async function callApi(apiName, data = null) {
    // Validate API configuration
    if (!apis[apiName]) {
        Swal.fire('Error', `API '${apiName}' not found.`, 'error');
        console.error(`API '${apiName}' not configured.`);
        return null;
    }

    const { url, method } = apis[apiName];
    let apiUrl = `${base_url}${url}`;

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
        const rawText = await response.text(); // Fetch raw text response for debugging

        console.log("Raw Response:", rawText);

        // Parse rawText as JSON
        const jsonData = JSON.parse(rawText);

        if (response.ok) {
            return jsonData; // Return the JSON data on success
        } else {
            Swal.fire('Error', jsonData.message || 'An error occurred.', 'error');
            return null;
        }
    } catch (error) {
        Swal.fire('Error', 'Network issue or API is unavailable.', 'error');
        console.error(`Network/API Error for '${apiName}':`, error);
        return null;
    }
}

export { callApi };
