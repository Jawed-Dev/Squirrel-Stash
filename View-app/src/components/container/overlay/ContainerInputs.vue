<template>
    <div class="flex">
        <!-- dépenses valeur -->
        <div class="flex flex-col w-full border-b-[1px] border-gray-700 px-1 py-[20px]">

            <div class="w-full flex justify-end items-center pr-3">
                <div class="flex items-center justify-center mt-[5px]">
                    <InputBase 
                    v-model="inputDateVal" 
                    width="w-fit"
                    extraClass="flex text-[12px] items-center text-center font-light" 
                    placeholder="Date"
                    id="input-date"
                    type="date"/>
                </div>
            </div>

            
            <div class="w-full">
                <label for="input-amount-purchase" class="flex justify-center text-[20px] font-extralight mt-[20px]">Montant en €</label>
                <div class="flex justify-center mt-[5px]">
                    <InputBase 
                    v-model="inputPriceVal" 
                    width="w-[8vw]"
                    extraClass="text-center font-light border-b-[1px]" 
                    placeholder="Montant"
                    id="input-amount-purchase"/>
                </div>
            </div>

            <div class="flex items-center gap-2 justify-center pt-[40px]">
                <!-- placeholder notes -->
                <div class="flex justify-center border-b-[1px]">
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
    import InputBase from '../../input/InputBase.vue';

    const inputNoteVal = defineModel('inputNoteVal');
    const inputPriceVal = defineModel('inputPriceVal');
    const inputDateVal = defineModel('inputDateVal');

</script>