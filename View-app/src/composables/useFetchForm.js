
const folderMain = "Projet_final_DWWM";

export default async function useFetch(form, method, dataForm, token = "") {

  try {

    // path
    const fullPath = `/api/${folderMain}/backend/?form=${form}`;  

    // header data
    const headers = new Headers();
    if(token) {
      headers.append("Authorization", `Bearer ${token}`);
    }
    headers.append("Content-Type", "application/json");

    // options
    const fetchOptions = {
      method: method,
      headers: headers
    };

    

    // data form
    if(method !== 'GET' && method !== 'HEAD') {
      fetchOptions.body = JSON.stringify(dataForm);
    }

    

    // fetch
    const response = await fetch(fullPath, fetchOptions);
    console.log(response);
    if (!response.ok) throw new Error(`HTTP error status: ${response.status}`);
    const data = await response.json();
    
    return data;
  } 
  
  catch (error) {
    console.error('Erreur lors de la récupération des données:', error);
    return null;
  }

}