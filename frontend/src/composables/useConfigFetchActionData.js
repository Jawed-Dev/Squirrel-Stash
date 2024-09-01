
import { NAME_FOLDER_MAIN } from '@/config.js';
import { createToast } from '@/composables/useToastNotification';
import { TEXT_SUBMIT_ERROR } from '@/errors/useHandleError';

export default async function useConfigFetchGetData(params) {
  try {
    if(!params.method) return null;
    if(!params.dataBody) return null;
    if(!params.token) params.token = '';
    if(!params.request) return null;

    // path
    const fullPath = `/api/${NAME_FOLDER_MAIN}/backend/?actionData=${params.request}`;  

    // header data
    const headers = new Headers();
    if(params.token) {
      headers.append("Authorization", `Bearer ${params.token}`);
    }
    headers.append("X-Custom-Origin", window.location.origin);
    headers.append("Content-Type", "application/json");

    // options
    const fetchOptions = {
      method: params.method,
      headers: headers
    };
    
    // data form accepted
    if(params.method !== 'GET' && params.method !== 'HEAD') {
      fetchOptions.body = JSON.stringify(params.dataBody);
    }

    // fetch
    const response = await fetch(fullPath, fetchOptions);
    if (!response.ok) {
      createToast(TEXT_SUBMIT_ERROR.FAIL_REQUEST, 'error');
      throw new Error(`Erreur de Statut HTTP: ${response.status}`);
    }
    const dataRequest = await response.json();
      
    return dataRequest;
  } 
  
  catch (error) {
    console.error('Erreur lors de la récupération des données:', error);
    return null;
  }

}