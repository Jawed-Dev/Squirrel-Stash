export function getLStorageAuthToken() {
    return localStorage.getItem('authToken') || '';
}  

export function setLStorageAuthToken(dataToken) {
    localStorage.setItem('authToken', dataToken);
}

export function setLStorageCookieConsent() {
    const now = new Date();

    const expirationDate = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
    const consentData = {
        consent: true,
        expiration: expirationDate.toISOString()
    };
    localStorage.setItem('cookieConsent', JSON.stringify(consentData));
}

export function getLStorageCookieConsent() {
    const consentString = localStorage.getItem('cookieConsent');
    if (consentString) return JSON.parse(consentString);
    return null;
}