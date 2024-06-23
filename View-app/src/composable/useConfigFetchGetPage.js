import { NAME_FOLDER_MAIN } from '@/config.js';

export default async function useConfigFetchGetPagePage(apiPath, token = "") {  
  try {
    
    // path
    const fullPath = `/api/${NAME_FOLDER_MAIN}/backend/?page=${apiPath}`;  

    // header data
    const headers = new Headers();
    if(token) {
      headers.append("Authorization", `Bearer ${token}`);
    }
    headers.append("X-Custom-Origin", window.location.origin);

    // options
    const fetchOptions = {
      method: 'GET',
      headers: headers
    };

    // fetch
    const response = await fetch(fullPath, fetchOptions);

    if (!response.ok) throw new Error(`HTTP error status: ${response.status}`);
    const data = await response.json();
    return data;
  } 

  catch (error) {
    console.error('Erreur lors de la récupération des données:', error);
    return null;
  }

}