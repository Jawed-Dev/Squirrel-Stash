import { NAME_FOLDER_MAIN } from '@/config.js';
import { createToast } from '@/composables/useToastNotification';
import { TEXT_SUBMIT_ERROR } from '@/errors/useHandleError';

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
    if (!response.ok) {
      createToast(TEXT_SUBMIT_ERROR.FAIL_REQUEST, 'error');
      throw new Error(`Erreur de Statut HTTP: ${response.status}`);
    }
    const data = await response.json();
    return data;
  } 

  catch (error) {
    console.error('Erreur lors de la récupération des données:', error);
    return null;
  }

}