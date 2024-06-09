
const folderMain = "Projet_final_DWWM";

export default async function useFetch(apiPath) {
  try {
    const fullPath = `/api/${folderMain}/backend/?page=${apiPath}`;  // Ajout du préfixe /api pour correspondre à la configuration du proxy
    //console.log('Fetching from:', fullPath);
    const response = await fetch(fullPath);

    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
    
    const data = await response.json();
    //console.log('Data:', data);
    return data;
  } 
  catch (error) {
    console.error('Erreur lors de la récupération des données:', error);
    return null;
  }
}