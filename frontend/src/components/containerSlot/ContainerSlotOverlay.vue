<template>
    <div>
        <div @click="$emit('toggleMenu','cancel')" class="text-lg flex justify-end cursor-pointer">
            <div v-if="!hideCross" class="p-2 bg-second-bg hover:bg-red-600 rounded-[3px]"> 
                <UseIconLoader nameIcon="Cross" :svg="{width:'20px', fill:'white'}"  />
            </div>
            <p v-else class="py-2"></p>
        </div>
        <div :class="`flex flex-col items-center shadow-red text-center ${bgHead} shadow-main py-2`">
            <h2 :class="`${width} flex justify-center text-2xl py-1 font-extralight`">{{titleContainer}}</h2>
        </div>
        
        <slot></slot>
    
        <div>
            <div v-if="!onlyOneBtn" class="flex shadow-main">
                <div class="w-1/2">
                    <button 
                        @click="$emit('toggleMenu','cancel')" 
                        class="w-full bg-btn-cancel py-[8px] rounded-bl-[3px] font-light hover:bg-opacity-50">{{textBtn1}}
                    </button>
                </div>
                <div class="w-1/2">
                    <button @click="$emit('toggleMenu','valid')" :class="`w-full hover:opacity-90 ${bgMainBtn} py-[8px] rounded-br-[3px] font-light`">{{textBtn2}}</button>
                </div>
            </div>
            <div v-else>
                <div class="w-full flex justify-center pb-3">
                    <button @click="$emit('toggleMenu','cancel')" :class="`w-1/3 hover:opacity-90 ${bgMainBtn} p-[8px] rounded-br-[3px] shadow-main`">{{textBtn1}}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>    
    import UseIconLoader from '@/composables/useIconLoader.vue';
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