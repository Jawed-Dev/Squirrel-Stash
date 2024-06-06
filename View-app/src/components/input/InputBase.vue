<template>
        <input
            @input="onInput" 
            :class="`shadow-black shadow-custom-lower py-[2px] flex items-center text-[16px] bg-transparent border-gray-400 border-[1px] rounded-[3px] ${width} pl-1 focus:outline-none ${props.extraClass}`"
            :value="modelValue"
            :type="type"
            :placeholder="placeholder"
            :id="id"
        >
</template>

<script setup>

    // variables, props, ...
    const model = defineModel()

    const props = defineProps({
        extraClass: { default:''},
        modelValue: { default:''},
        type: {default: 'text'},
        placeholder: {default: ''},
        width: {default:''},
        id: {default:''}
    });
   
    // functions
    function onInput(event) {
        model.value = event.target.value;
    }
</script>


