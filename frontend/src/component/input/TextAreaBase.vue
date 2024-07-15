<template>
    <div class="pl-1 flex items-center w-full" :class="`p-1 border rounded-sm text-white font-light`">
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
});

const model = defineModel();

// life cycle functions
function onInput(event) {
    model.value = event.target.value;
}

const border = computed(() => {
    return (!props.borderHidden) ? 'border-b' : 'border-none';
});
</script>


