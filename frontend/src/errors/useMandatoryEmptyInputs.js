import { ref, computed } from 'vue';

export function useMandatoryEmptyInputs(inputs) {
  const stateEmptyInputs = ref([]);

  const mandatoryEmptyInputs = () => {
    const emptyInputs = [];
    for (const input of inputs) {
      if (!input?.ref.value) {
        emptyInputs.push({ emptyInput: input.name });
      }
    }
    return emptyInputs.length > 0 ? emptyInputs : null;
  };

const computedEmptyInputs = computed(() => {
    const emptyInputs = mandatoryEmptyInputs();
    if(emptyInputs) {
        stateEmptyInputs.value = emptyInputs;
        return emptyInputs;
    }
    else {
        stateEmptyInputs.value = [];
        return [];
    }
});

  return {
    computedEmptyInputs,
    stateEmptyInputs,
  };
}