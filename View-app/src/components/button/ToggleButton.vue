<template>
    <div class="flex border-b border-main-color border shadow-black shadow-custom-main rounded-r-md rounded-l-md">
        <p class="w-[10vw] text-center px-3 py-1 cursor-pointer rounded-l-md" :class="[!typeTransaction ? 'bg-gradient-blue' : '']" @click="handleStateToggle(false)" >{{ text1 }}</p>
        <p class="w-[10vw] text-center px-3 py-1 cursor-pointer rounded-r-md" :class="[!typeTransaction ? '' : 'bg-gradient-vanusa']" @click="handleStateToggle(true)" >{{ text2 }}</p>
    </div>
</template>


<script setup>
    // import
    import { ref } from 'vue';

    // functions, props ...
    const typeTransaction = defineModel('typeTransaction');

    const props = defineProps({
        text1: {default: 'text 1'},
        text2: {default: 'text 2'}
    })
    
    // functions
    function handleStateToggle(state) {
        typeTransaction.value = state;
        //emit('update:modelValue', state);
    }   
</script>