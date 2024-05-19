<template>
    <div class="flex border-b border-main-color border w-fit shadow-black shadow-custom-test rounded-r-md rounded-l-md">
        <p class="px-3 py-1 cursor-pointer rounded-l-md" :class="[stateTabPurchase ? 'bg-gradient-blue' : '']" @click="handleStateTab(true)" >{{ text1 }}</p>
        <p class="px-3 py-1 cursor-pointer rounded-r-md" :class="[stateTabPurchase ? '' : 'bg-gradient-blue']" @click="handleStateTab(false)" >{{ text2 }}</p>
    </div>
</template>


<script setup>
    // import
    import { ref } from 'vue';

    // functions, props ...
    const stateTabPurchase = ref(true);

    const emit = defineEmits(['update:modelValue']);

    const props = defineProps({
        text1: {default: 'text 1'},
        text2: {default: 'text 2'}
    })
    

    // functions
    function handleStateTab(state) {
        stateTabPurchase.value = state;
        emit('update:modelValue', state);
    }   
</script>