<template>
        <input
            @input="onInput" 
            :class="`flex items-center text-[16px] bg-transparent border-white ${width} pl-1 focus:outline-none ${props.extraClass}`"
            :value="modelValue"
            :type="type"
            :placeholder="placeholder"
            :id="props.id"
        >
</template>

<script setup>

    // variables, props, ...
    const model = defineModel()

    const props = defineProps({
        extraClass: { default:'' },
        modelValue: { default:''},
        type: {default: 'text'},
        placeholder: {default: ''},
        width: {default:''},
        id: {default:''},
    });
   
    // life cycle functions
    function onInput(event) {
        model.value = event.target.value;
        //console.log(event.target.value, typeof(event.target.value));
    }
</script>


