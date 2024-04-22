<template>
    <input type="checkbox" v-model="state" :class="`mr-1 ${extraClass}`" >
</template>

<script setup>
    import { ref } from 'vue';
    import { useBoolean } from '../../composables/useBoolean';

    const props = defineProps(['extraClass']);
    
    function handleInput(action, param = null) {
        switch(action) {
            case 'getState' : {
                return state.value;
            }
            case 'setState' : {
                updateBoolean(param);
                break;
            }
        }
    }

    defineExpose({
        handleInput
    });

    const { updateBoolean, state} = useBoolean();
</script>
