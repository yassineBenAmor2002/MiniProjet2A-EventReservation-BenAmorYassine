// ===============================
// Conversion base64url <-> buffer
// ===============================

function bufferToBase64Url(buffer) {
    const bytes = new Uint8Array(buffer);
    let binary = '';
    bytes.forEach(b => binary += String.fromCharCode(b));

    return btoa(binary)
        .replace(/\+/g, '-')
        .replace(/\//g, '_')
        .replace(/=+$/, '');
}

function base64UrlToBuffer(base64url) {
    let base64 = base64url
        .replace(/-/g, '+')
        .replace(/_/g, '/');

    const padding = '='.repeat((4 - base64.length % 4) % 4);
    base64 += padding;

    const binary = atob(base64);
    const bytes = Uint8Array.from(binary, c => c.charCodeAt(0));

    return bytes.buffer;
}

// ===============================
// REGISTER PASSKEY
// ===============================

async function registerPasskey(email) {
    const res = await fetch('/api/auth/register/options', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email })
    });

    const options = await res.json();

    const credential = await navigator.credentials.create({
        publicKey: {
            ...options,
            challenge: base64UrlToBuffer(options.challenge),
            user: {
                ...options.user,
                id: base64UrlToBuffer(options.user.id)
            }
        }
    });

    const verify = await fetch('/api/auth/register/verify', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            email,
            credential: {
                id: credential.id,
                rawId: bufferToBase64Url(credential.rawId),
                type: credential.type,
                response: {
                    clientDataJSON: bufferToBase64Url(credential.response.clientDataJSON),
                    attestationObject: bufferToBase64Url(credential.response.attestationObject)
                }
            }
        })
    });

    const data = await verify.json();

    if (data.token) {
        localStorage.setItem('jwt_token', data.token);
        localStorage.setItem('refresh_token', data.refresh_token);
    }

    return data;
}

// ===============================
// LOGIN PASSKEY
// ===============================

async function loginWithPasskey() {
    const res = await fetch('/api/auth/login/options', {
        method: 'POST'
    });

    const options = await res.json();

    const assertion = await navigator.credentials.get({
        publicKey: {
            ...options,
            challenge: base64UrlToBuffer(options.challenge)
        }
    });

    const verify = await fetch('/api/auth/login/verify', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            credential: {
                id: assertion.id,
                rawId: bufferToBase64Url(assertion.rawId),
                type: assertion.type,
                response: {
                    clientDataJSON: bufferToBase64Url(assertion.response.clientDataJSON),
                    authenticatorData: bufferToBase64Url(assertion.response.authenticatorData),
                    signature: bufferToBase64Url(assertion.response.signature),
                    userHandle: assertion.response.userHandle
                        ? bufferToBase64Url(assertion.response.userHandle)
                        : null
                }
            }
        })
    });

    const data = await verify.json();

    if (data.token) {
        localStorage.setItem('jwt_token', data.token);
        localStorage.setItem('refresh_token', data.refresh_token);
    }

    return data;
}

// ===============================
// FETCH AVEC JWT
// ===============================

function authFetch(url, options = {}) {
    const token = localStorage.getItem('jwt_token');

    return fetch(url, {
        ...options,
        headers: {
            ...(options.headers || {}),
            'Authorization': token ? `Bearer ${token}` : ''
        }
    });
}

// ===============================
// REFRESH TOKEN
// ===============================

async function refreshToken() {
    const refresh = localStorage.getItem('refresh_token');
    if (!refresh) return false;

    const res = await fetch('/api/token/refresh', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ refresh_token: refresh })
    });

    if (!res.ok) {
        localStorage.removeItem('jwt_token');
        localStorage.removeItem('refresh_token');
        return false;
    }

    const data = await res.json();

    localStorage.setItem('jwt_token', data.token);

    if (data.refresh_token) {
        localStorage.setItem('refresh_token', data.refresh_token);
    }

    return true;
}