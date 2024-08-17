<template>
    <div :class="` ${colorBorder} bg-second-bg transition-all duration-500 flex 
    items-center w-full p-1 border-2 rounded-lg text-white font-light`">
        <textarea 
            @input="onInput" 
            :class="`${props.extraClass} bg-transparent pb-28 pl-1 focus:outline-none w-full ${width}`"
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
const mandatoryInput = defineModel('mandatoryInput');

// life cycle functions

const colorBorder = computed(() => {
    if(model.value) mandatoryInput.value = false;
    if(mandatoryInput.value) return 'border-custom-orange'
    if(props.borderHidden) return '';
    return (props.modelValue) ? "border-custom-green" : "hover:border-custom-blue border-slate-600";
});

function onInput(event) {
    model.value = event.target.value;
}

</script>


