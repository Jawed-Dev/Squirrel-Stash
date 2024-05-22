
<template>
    <div >
        <input
            @input="onInput" 
            :class="extraClass"
            :value="modelValue"
            :type="type"
            :placeholder="placeholder"
        >
    </div>
</template>

<script setup>
    import { ref } from 'vue';

    const model = defineModel()

    const props = defineProps({
        extraClass: { default:''},
        modelValue: { default:''},
        type: {default: 'text'},
        placeholder: {default: ''}
    });
   

    const emit = defineEmits('update:modelValue');
    function onInput(event) {
        model.value = event.target.value;
        //alert(event.target.value);
        //emit(model.value);
        //emit('update:modelValue', event.target.value);
    }

    

</script>


