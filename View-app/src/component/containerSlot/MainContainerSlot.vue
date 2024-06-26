<template>
    <div :class="`flex flex-col items-center rounded-t-[3px] shadow-red ${bgHead} shadow-black shadow-custom-main py-[2vh]`">
        <h2 :class="`${width} flex justify-center text-[22px] pt-3 py-2 font-extralight`">{{titleContainer}}</h2>
    </div>
    <slot></slot> <!-- content slot -->
    <div>
        <div class="flex shadow-black shadow-custom-main">
            <div class="w-[50%]">
                <button @click="$emit('toggleMenu','cancel')" class="w-full bg-[#1e3c72] h-full py-[3%] rounded-bl-[3px] font-light">{{textBtn1}}</button>
            </div>
            <div class="w-[50%]">
                <button @click="$emit('toggleMenu','valid')" :class="`w-full ${bgMainBtn}  h-full py-[3%] rounded-br-[3px] font-light`">{{textBtn2}}</button>
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
        bgHead: { default: 'bg-main-blue'},
        typeTransaction: { default: false },
        bgMainBtn: {default: 'bg-gradient-blue'},
        width: {default: '250px'},
    });
</script>