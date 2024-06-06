<template>
    <div class="flex">
        <!-- dépenses valeur -->
        <div class="flex flex-col w-full border-b-[1px] border-gray-700 px-1 py-4">
            <div class="w-full">
                <label for="input-amount-purchase" class="flex justify-center text-[20px] font-extralight">Montant en €</label>
                <div class="flex justify-center mt-2">
                    <InputBase 
                    v-model="inputPriceVal" 
                    width="w-[150px]"
                    extraClass="text-center font-light " 
                    placeholder="Montant"
                    id="input-amount-purchase"/>
                </div>
            </div>
            <div class="flex items-center justify-center pt-[5%]">
                <div class="flex justify-center items-center ">
                    <IconWrite :svg="svgVerySmallWhite"/>
                </div>
                
                <!-- placeholder notes -->
                <div class="flex justify-center w-[80%]">
                    <InputBase v-model="inputNoteVal"
                    width="w-[90%]"
                    extraClass="font-light"
                    placeholder="Note"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { svgConfig } from '@/functions/svg/svgConfig';
    import InputBase from '../../input/InputBase.vue';
    import IconWrite from '../../svgs/IconWrite.vue';

    const inputNoteVal = defineModel('inputNoteVal');
    const inputPriceVal = defineModel('inputPriceVal');

    const svgVerySmallWhite = svgConfig.setColorDynamic(svgConfig.verySmallIcon, 'white');


</script>