<template>
    <div class="flex justify-center shadow-black shadow-custom-main rounded-r-md rounded-l-md w-full mx-20">
        <div class="w-[50%]">
            <p class="w-full text-center px-3 py-1 cursor-pointer rounded-l-md " 
            :class="[!typeTransaction ? 'bg-gradient-blue' : '']" @click="handleStateToggle(false)" >{{ text1 }}</p>
        </div>

        <div class="w-[50%]">
            <p class="w-full text-center px-3 py-1 cursor-pointer rounded-r-md" 
            :class="[!typeTransaction ? '' : 'bg-gradient-vanusa']" @click="handleStateToggle(true)" >{{ text2 }}</p>
        </div>
    </div>
</template>


<script setup>
    // functions, props ...
    const typeTransaction = defineModel('typeTransaction');
    const props = defineProps({
        text1: {default: 'text 1'},
        text2: {default: 'text 2'}
    })
    
    // functions
    function handleStateToggle(state) {
        typeTransaction.value = state;
    }   
</script>