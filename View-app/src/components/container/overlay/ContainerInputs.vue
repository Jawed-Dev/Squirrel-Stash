<template>
    <div class="flex">
        <!-- dépenses valeur -->
        <div class="flex flex-col w-full border-b-[1px] border-gray-700 px-1 py-[40px]">
            <div class="w-full">
                <label for="input-amount-purchase" class="flex justify-center text-[20px] font-extralight">Montant en €</label>
                <div class="flex justify-center mt-[20px]">
                    <InputBase 
                    v-model="inputPriceVal" 
                    width="w-[8vw]"
                    extraClass="text-center font-light " 
                    placeholder="Montant"
                    id="input-amount-purchase"/>
                </div>
            </div>
            <div class="flex items-center gap-2 justify-center pt-[40px]">
                <!-- placeholder notes -->
                <div class="flex justify-center">
                    <InputBase v-model="inputNoteVal"
                    width="w-[23vw]"
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

    const inputNoteVal = defineModel('inputNoteVal');
    const inputPriceVal = defineModel('inputPriceVal');

    const svgVerySmallWhite = svgConfig.setColorDynamic(svgConfig.verySmallIcon, 'white');


</script>