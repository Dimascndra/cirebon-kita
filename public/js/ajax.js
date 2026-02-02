function getHeaders() {
    const headers = {
        "Content-Type": "application/json",
        Accept: "application/json",
    };
    const token = localStorage.getItem("auth_token");
    if (token && token !== "undefined" && token !== "null") {
        headers["Authorization"] = `Bearer ${token}`;
    }

    // Add CSRF Token for Web Routes
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        headers["X-CSRF-TOKEN"] = csrfToken.getAttribute("content");
    }

    return headers;
}

function ajaxGet(url, onSuccess, onError) {
    fetch(url, {
        method: "GET",
        headers: getHeaders(),
    })
        .then(handleResponse)
        .then((data) => {
            if (data.success) {
                onSuccess(data.data); // Unwrap data.data
            } else {
                if (onError) onError(data);
            }
        })
        .catch((error) => {
            console.error("Fetch Error:", error);
            if (onError) onError(error);
        });
}

function ajaxPost(url, body, onSuccess, onError) {
    fetch(url, {
        method: "POST",
        headers: getHeaders(),
        body: JSON.stringify(body),
    })
        .then(handleResponse)
        .then((data) => {
            if (data.success) {
                onSuccess(data.data); // Unwrap data.data
            } else {
                if (onError) onError(data);
            }
        })
        .catch((error) => {
            console.error("Fetch Error:", error);
            if (onError) onError(error);
        });
}

function handleResponse(response) {
    if (response.status === 401) {
        // Unauthorized - Clear token and redirect if needed
        localStorage.removeItem("auth_token");
        // window.location.href = '/login'; // Optional: Auto redirect
    }
    return response.json().then((data) => {
        if (!response.ok) {
            const error = new Error(data.message || response.statusText);
            error.errors = data.errors; // Attach validation errors
            throw error;
        }
        return data;
    });
}
