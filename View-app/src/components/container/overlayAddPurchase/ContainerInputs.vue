<template>
    <div class="flex ">
        <!-- dépenses valeur -->
        <div class="flex flex-col w-[100%] border-b-[1px] px-1 py-7">
            <div class="w-full">
                <p class="flex justify-center text-[20px] font-light ">Montant en €</p>
                <div class="flex justify-center mt-2">
                    <inputForm v-model="inputPriceVal" extraClass="shadow-black shadow-custom-main w-[150px] flex items-center font-extralight text-[17px] bg-transparent border-white border rounded-[3px] text-center w-[100px] focus:outline-none" placeholder="Montant"/>
                </div>
            </div>
            <div class="flex gap-3 items-center justify-center pt-14">
                <div class="flex gap-2 items-center">
                    <IconWrite :svg="svgVerySmallWhite"/>
                </div>
                
                <!-- placeholder notes -->
                <div class="font-extralight text-[17px]">
                    <inputForm v-model="inputNoteVal" 
                    extraClass="shadow-black shadow-custom-main flex items-center text-[15px] bg-transparent border-white border-[1px] rounded-[3px] w-[500px] pl-1 focus:outline-none" 
                    placeholder="Note"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { svgConfig } from '@/functions/svg/svgConfig';
    import inputForm from '../../input/inputForm.vue';
    import IconWrite from '../../svgs/IconWrite.vue';

    const inputNoteVal = defineModel('inputNoteVal');
    const inputPriceVal = defineModel('inputPriceVal');

    const svgVerySmallWhite = svgConfig.setColorDynamic(svgConfig.verySmallIcon, 'white');


</script>