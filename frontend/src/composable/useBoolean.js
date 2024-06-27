import { ref } from "vue";

export function useBoolean() {
    const state = ref(false);

    function toggleState() {
        state.value = !state.value;
    }

    function updateBoolean(newValue) {
        state.value = newValue;
    }

    return {
        updateBoolean,
        state
    }
}