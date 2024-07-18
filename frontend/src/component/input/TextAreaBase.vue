<template>
    <div :class="` ${colorBorder} transition-all duration-500 flex items-center w-full p-1 border rounded-sm text-white font-light`">
        <textarea 
            @input="onInput" 
            :class="`${props.extraClass} bg-transparent pb-5 pl-1 focus:outline-none w-full ${width}`"
            :value="modelValue"
            :placeholder="placeholder"
            :id="props.id"
        ></textarea>
    </div>
</template>

<script setup>
import { computed } from 'vue';

// variables, props, ...

const props = defineProps({
    extraClass: { default:'' },
    modelValue: { default:''},
    placeholder: {default: ''},
    width: {default:''},
    id: {default:''},
    borderHidden: {default: false },
    
});

const model = defineModel();

// life cycle functions

const colorBorder = computed(() => {
    if(props.borderHidden) return '';
    return (props.modelValue) ? "border-custom-blue" : "hover:border-custom-blue border-custom-gray-2";
});

function onInput(event) {
    model.value = event.target.value;
}

</script>


