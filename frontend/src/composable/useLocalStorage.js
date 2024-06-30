export function getLStorageAuthToken() {
    return localStorage.getItem('authToken') || '';
}  

export function setLStorageAuthToken(dataToken) {
    localStorage.setItem('authToken', dataToken);
}