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

            <div class="flex flex-col items-center mt-[25px] w-full">
                <label for="input-amount-purchase" 
                class="flex text-[20px] font-extralight">Montant en €</label>
                <InputBase 
                v-model="inputPriceVal" 
                extraClass="border-b-2 py-1 text-white font-light mt-[2px] :placeholder text-center" 
                placeholder="Montant"
                type="text"
                id="input-amount-purchase"/>
            </div>

            <div class="flex items-center gap-2 justify-center pt-[25px] w-full">
                <!-- placeholder notes -->
                <div class="flex justify-center w-full">
                    <InputBase v-model="inputNoteVal"
                    extraClass="border-b-2 w-[80%] py-1 text-white font-light mt-[2px]"
                    type="text"
                    placeholder="Note"/>
                </div>
            </div>
            
        </div>
    </div>
</template>

<script setup>
    import InputBase from '@/component/input/InputBase.vue';
    const inputNoteVal = defineModel('inputNoteVal');
    const inputPriceVal = defineModel('inputPriceVal');
    const inputDateVal = defineModel('inputDateVal');
</script>