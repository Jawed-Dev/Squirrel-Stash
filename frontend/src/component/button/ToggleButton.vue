<template>
    <div class="flex justify-center shadow-black shadow-custom-main rounded-r-md rounded-l-md w-full">
        <div class="w-1/2">
            <p class="w-full text-center px-2 py-[2px] cursor-pointer rounded-l-md text-[15px] font-light overflow-hidden text-ellipsis" 
            :class="[!typeTransaction ? 'bg-gradient-blue' : '']" @click="handleStateToggle(false)" >{{ text1 }}</p>
        </div>

        <div class="w-1/2">
            <p class="w-full text-center px-2 py-[2px] cursor-pointer rounded-r-md text-[15px] font-light overflow-hidden text-ellipsis" 
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