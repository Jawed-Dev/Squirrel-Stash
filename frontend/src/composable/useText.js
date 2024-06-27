// useText.js
import { ref } from 'vue';

export function useText(initialValue = '') {
    const text = ref(initialValue);

    function updateText(newValue) {
        text.value = newValue;
    }

    function resetText() {
        text.value = '';
    }

    return { text, updateText, resetText };
}