<template>
    <div @click="$emit('toggleMenu','cancel')" class="text-lg flex justify-end cursor-pointer">
        <div v-if="!hideCross" class="p-2 bg-second-bg hover:bg-red-600 "> 
            <UseIconLoader nameIcon="Cross" :svg="{width:'20px', fill:'white'}"  />
        </div>
        <p v-else class="py-2"></p>
    </div>
    <div :class="`flex flex-col items-center shadow-red ${bgHead} shadow-black shadow-custom-main py-2`">
        <h2 :class="`${width} flex justify-center text-xl py-1 font-extralight`">{{titleContainer}}</h2>
    </div>
    
    <slot></slot>

    <div>
        <div v-if="!onlyOneBtn" class="flex shadow-black shadow-custom-main">
            <div class="w-1/2">
                <button @click="$emit('toggleMenu','cancel')" class="w-full bg-[#1e3c72] py-[8px] rounded-bl-[3px] font-light">{{textBtn1}}</button>
            </div>
            <div class="w-1/2">
                <button @click="$emit('toggleMenu','valid')" :class="`w-full ${bgMainBtn}  py-[8px] rounded-br-[3px] font-light`">{{textBtn2}}</button>
            </div>
        </div>
        <div v-else>
            <div class="w-full flex justify-center pb-3">
                <button @click="$emit('toggleMenu','cancel')" :class="`w-1/3 ${bgMainBtn} p-[8px] rounded-br-[3px] shadow-black shadow-custom-main`">{{textBtn1}}</button>
            </div>
        </div>
    </div>
</template>

<script setup>    
    import UseIconLoader from '@/composable/useIconLoader.vue';
    // variables, props, ...
    const emits = defineEmits(['toggleMenu']);
    const props = defineProps({
        toggleMenu: Function,
        titleContainer: { default: ''},
        textBtn1: { default: ''},
        textBtn2: { default: ''},
        bgHead: { default: 'bg-gradient-x-blue'},
        typeTransaction: { default: false },
        bgMainBtn: {default: 'bg-gradient-blue'},
        width: {default: '250px'},
        onlyOneBtn: {default: false},
        hideCross: {default: false}
    });
</script>