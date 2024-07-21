import { storeAuthTOken } from "@/storePinia/useStoreDashboard"; 

export function getLStorageAuthToken() {
    const authToken = storeAuthTOken();
    //return localStorage.getItem('authToken') || '';
    return authToken.token || '';
}  

export function setLStorageAuthToken(token) {
    const authToken = storeAuthTOken();
    authToken.token = token;
    //localStorage.setItem('authToken', dataToken);
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