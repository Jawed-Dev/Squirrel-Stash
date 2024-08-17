
import { NAME_FOLDER_MAIN } from '@/config.js';
import { createToast } from '@/composable/useToastNotification';
import { TEXT_SUBMIT_ERROR } from '@/error/useHandleError';

export default async function useConfigFetchGetData(params) {
  try {
    //if(!params.form) return null;
    if(!params.method) return null;
    if(!params.dataBody) return null;
    if(!params.token) params.token = '';
    if(!params.request) return null;

    // path
    const fullPath = `/api/${NAME_FOLDER_MAIN}/backend/?getData=${params.request}`;  

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
    //console.log(response);
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