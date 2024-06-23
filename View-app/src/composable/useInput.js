import { ref } from "vue";

export function useInput(emit) {
    const inputValue = ref('');
    

    function handleInput(event) {
        inputValue.value = event.target.value;  // Mettre à jour la valeur réactive
        emit('inputUpdate', inputValue.value);  // Émettre la valeur mise à jour
    }

    return {
        handleInput, inputValue
    }
}