
import { NAME_FOLDER_MAIN } from '@/config.js';

export default async function useConfigFetchGetData(params) {
  try {
    if(!params.form) return null;
    if(!params.method) return null;
    if(!params.dataForm) return null;
    if(!params.token) params.token = '';

    // path
    const fullPath = `/api/${NAME_FOLDER_MAIN}/backend/?getData=${params.form}`;  

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
      fetchOptions.body = JSON.stringify(params.dataForm);
    }

    // fetch
    const response = await fetch(fullPath, fetchOptions);
    //console.log(response);
    if (!response.ok) throw new Error(`Erreur de Statut HTTP: ${response.status}`);
    const data = await response.json();
    return data;
  } 
  
  catch (error) {
    console.error('Erreur lors de la récupération des données:', error);
    return null;
  }

}