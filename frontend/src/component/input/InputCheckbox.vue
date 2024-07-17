<template>
        <input
            @change="onChange" 
            :class="`flex items-center text-[16px] bg-transparent border-white ${width} pl-1 ${props.extraClass}`"
            :checked="modelValue"
            type="checkbox"
            :id="props.id"
        >
</template>

<script setup>

    // variables, props, ...
    const model = defineModel()
    const props = defineProps({
        extraClass: { default:'' },
        modelValue: { default:''},
        width: {default:''},
        id: {default:''},
    });
    // life cycle functions
    function onChange(event) {
        model.value = event.target.checked;
    }
</script>


