<template>
    <div class="flex">
        <!-- dépenses valeur -->
        <div class="flex items-center flex-col w-full border-b-[1px] border-gray-700">

            <div class="flex justify-center mt-6 min-[620px]:justify-end w-full min-[620px]:mt-5">
                <div class="w-1/2 min-[620px]:w-[35%] mr-1">
                    <InputBase 
                        extraClass="text-[14px] py-[1px]"
                        v-model="inputDateVal" 
                        v-model:stateError="errorInputs.inputDateVal" 
                        v-model:mandatoryInput="mandatoryInputs.inputDateVal"
                        width="w-full"
                        placeholder="Date"
                        id="input-date"
                        type="date"
                        validFormat="trsDate"
                        :hideAnimation="true"
                        iconName="Calendar"
                    />
                </div>

                
                
            </div>

            <div class="w-1/2 mt-4 min-[600px]:mt-3">
                <div class="w-full">
                    <label for="input-amount-trs" class="pl-2 text-[16px] font-light">Montant</label>
                    <InputBase 
                        extraClass="text-[14px] py-[2px]"
                        iconName="Amount"
                        v-model="inputPriceVal" 
                        v-model:stateError="errorInputs.inputPriceVal" 
                        v-model:mandatoryInput="mandatoryInputs.inputPriceVal"
                        placeholder="Votre montant"
                        type="text"
                        id="input-amount-trs"
                        validFormat="amount"
                        :hideAnimation="true"
                        :onlyNumbers="true"
                    />
                </div>
            </div>

            <div class="w-1/2 flex flex-col mt-4 mb-10">
                <!-- placeholder note -->
                <div class="w-full">
                    <label for="input-note-trs" class="pl-2 text-[16px] font-light">Note</label>
                    <InputBase 
                        extraClass="text-[14px] py-[2px]"
                        iconName="Pencil"
                        v-model="inputNoteVal"
                        v-model:stateError="errorInputs.inputNoteVal" 
                        type="text"
                        placeholder="Votre note"
                        id="input-note-trs"
                        validFormat="note"
                        :hideAnimation="true"
                    />
                
                </div>
            </div>
            
        </div>
    </div>
</template>

<script setup>
    import InputBase from '@/component/input/InputBase.vue';
    const props = defineProps({
        infoTransaction: { default: []},
        typeTransaction: { default: false}
    });
    
    const inputNoteVal = defineModel('inputNoteVal');
    const inputPriceVal = defineModel('inputPriceVal');
    const inputDateVal = defineModel('inputDateVal');
    const errorInputs = defineModel('errorInputs');
    const mandatoryInputs = defineModel('mandatoryInputs');
</script>



