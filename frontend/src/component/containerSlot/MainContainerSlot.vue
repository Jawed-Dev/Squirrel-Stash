<template>
    <div @click="$emit('toggleMenu','cancel')" class="text-[14px] flex justify-end pr-2 cursor-pointer py-1">
        <p v-if="!hideCross" class="py-1 px-1"> ❌</p>
        <p v-else class="py-2"></p>
    </div>
    <div :class="`flex flex-col items-center shadow-red ${bgHead} shadow-black shadow-custom-main py-[10px]`">
        <h2 :class="`${width} flex justify-center text-[22px] py-1 font-extralight`">{{titleContainer}}</h2>
    </div>
    
    <slot></slot>

    <div class="">
        <div v-if="!onlyOneBtn" class="flex shadow-black shadow-custom-main">
            <div class="w-1/2">
                <button @click="$emit('toggleMenu','cancel')" class="w-full bg-[#1e3c72] py-[10px] rounded-bl-[3px] font-light">{{textBtn1}}</button>
            </div>
            <div class="w-1/2">
                <button @click="$emit('toggleMenu','valid')" :class="`w-full ${bgMainBtn}  py-[10px] rounded-br-[3px] font-light`">{{textBtn2}}</button>
            </div>
        </div>
        <div v-else>
            <div class="w-full">
                <button @click="$emit('toggleMenu','cancel')" :class="`w-full ${bgMainBtn} py-[10px] rounded-br-[3px]`">{{textBtn1}}</button>
            </div>
        </div>
    </div>
</template>

<script setup>    
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