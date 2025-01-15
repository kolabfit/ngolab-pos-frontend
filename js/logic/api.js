export async function callApi(url, options = {}) {
    try {
        // Default options
        const defaultOptions = {
            method: "GET", // Default method
            headers: {
                "Content-Type": "application/json", // Default header
            },
            body: null, // Body (for POST, PUT, etc.)
        };

        // Merge default options with user-defined options
        const requestOptions = {
            ...defaultOptions,
            ...options,
            headers: {
                ...defaultOptions.headers,
                ...(options.headers || {}),
            },
        };

        // If the body exists and is an object, stringify it
        if (
            requestOptions.body &&
            typeof requestOptions.body === "object" &&
            !(requestOptions.body instanceof FormData)
        ) {
            requestOptions.body = JSON.stringify(requestOptions.body);
        }

        // if the body is form data, remove the content-type header
        if (requestOptions.body instanceof FormData) {
            delete requestOptions.headers["Content-Type"];
        }

        // Perform the API request
        const response = await fetch("https://ngolab.id" + url, requestOptions);

        // Check if the response is ok (status in the range 200–299)
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        // Parse the response as JSON
        return await response.json();
    } catch (error) {
        console.error("Error calling API:", error.message);
        throw error; // Re-throw the error for handling elsewhere
    }
}
