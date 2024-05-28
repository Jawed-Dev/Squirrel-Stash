<template>
    <div class="rounded-t-[3px] shadow-black shadow-custom-main trigger-class-AddPurchase bg-gradient-joomla py-[36px] px-[150px]">
        <h2 class="w-[250px] font-extralight text-[22px] text-center">{{titleContainer}}</h2>
    </div>
    <slot></slot>
    <div class="pt-2">
        <div class="flex shadow-black shadow-custom-main">
            <div class="w-[50%]">
                <button @click="$emit('toggleMenu','cancel')" class="w-full bg-gradient-namn h-full py-2 rounded-bl-[3px]">{{textBtn1}}</button>
            </div>
            <div class="w-[50%]">
                <button @click="toggleMenu('valid')" class="w-full bg-gradient-blue h-full py-2 rounded-br-[3px]">{{textBtn2}}</button>
            </div>
        </div>
    </div>
</template>

<script setup>
    const props = defineProps({
        toggleMenu: Function,
        titleContainer: { default: ''},
        textBtn1: { default: ''},
        textBtn2: { default: ''},
    });

</script>