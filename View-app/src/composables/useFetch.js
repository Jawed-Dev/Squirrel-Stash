import { ref } from "vue";

export function useFetch() {
    const isFetching = ref(false);
    const isSuccess = ref(false);
    // error à ajouter

    async function fetchData(data) {
        isFetching.value = true;
        isSuccess.value = false;

        const response = await fetch(apiEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if(!response.ok) return null;

        const responseData = await response.json();
        isSuccess.value = true;

        return responseData;
    }


    return {
        isFetching, 
        isSuccess,
        fetchData
    }

}