<template>
    <div :class="`${props.width} rounded-[3px] overflow-hidden mt-5 shadow-black shadow-custom-main`">
        <div :class="`flex flex-col gradient-border overflow-hidden bg-main-gradient`">
            <div class="flex items-center justify-between px-2 py-2 ">
                <UseIconLoader 
                    :nameIcon="svg.name" :svg="svg" 
                    :class="`${svg.color} p-[0.7vw] rounded-lg shadow-black shadow-custom-main`"
                />
                <div class="flex flex-col items-end gap-3">
                    <SetThreshold width="w-[30vw]" :isIconActive="isIconActive" />
                    <p :class="`pl-[12px] ${colorValue} text-[18px] font-light`">{{ amountValue }}</p>
                </div>
            </div>
    
            <div class="pl-[13px] border-t py-1 pr-2 flex items-center justify-end font-extralight text-[15px] border-gray-700 ">
                <p class=" text-white"> {{ nameStat }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>

    import SetThreshold from '@/component/overlay/SetThreshold.vue';
    import UseIconLoader from '@/composable/useIconLoader.vue';
    
    // variables, props...
    const props = defineProps({
        svg : { default: {} },
        nameStat : { default: '' },
        amountValue : { default: 0 },
        colorValue : { default: '' },
        width: {default: ''},
        isIconActive: {default: false}
    });
</script>