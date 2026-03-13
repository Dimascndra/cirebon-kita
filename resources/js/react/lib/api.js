import axios from 'axios';

const TOKEN_KEY = 'cirebon-kita-token';
const USER_KEY = 'cirebon-kita-user';

const api = axios.create({
    baseURL: '/api',
    headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

function getStoredToken() {
    return window.localStorage.getItem(TOKEN_KEY);
}

export function setAuthToken(token) {
    if (token) {
        window.localStorage.setItem(TOKEN_KEY, token);
        api.defaults.headers.common.Authorization = `Bearer ${token}`;
        return;
    }

    window.localStorage.removeItem(TOKEN_KEY);
    delete api.defaults.headers.common.Authorization;
}

export function getStoredUser() {
    const raw = window.localStorage.getItem(USER_KEY);

    if (!raw) {
        return null;
    }

    try {
        return JSON.parse(raw);
    } catch {
        return null;
    }
}

export function setStoredUser(user) {
    if (user) {
        window.localStorage.setItem(USER_KEY, JSON.stringify(user));
        return;
    }

    window.localStorage.removeItem(USER_KEY);
}

const token = getStoredToken();
if (token) {
    api.defaults.headers.common.Authorization = `Bearer ${token}`;
}

export { api, TOKEN_KEY, USER_KEY };
