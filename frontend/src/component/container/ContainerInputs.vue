<template>
    <div class="flex">
        <!-- dépenses valeur -->
        <div class="flex items-center flex-col w-full border-b-[1px] border-gray-700">

            <div class="flex justify-center mt-6 min-[500px]:justify-end w-full min-[500px]:mt-2">
                <div class="w-1/2 min-[500px]:w-[35%] mr-1">
                    <InputBase 
                        extraClass="text-[14px] py-[1px]"
                        v-model="inputDateVal" 
                        v-model:stateError="errorInputs.inputDateVal" 
                        width="w-full"
                        placeholder="Date"
                        id="input-date"
                        type="date"
                        validFormat="trsDate"
                        :hideAnimation="true"
                    />
                </div>
            </div>

            <div class="w-1/2 mt-4 min-[500px]:mt-0">
                <div class="w-full">
                    <label for="input-amount-trs" class="pl-2 text-[16px] font-light">Montant</label>
                    <InputBase 
                        extraClass="text-[14px] py-[2px]"
                        iconName="Amount"
                        v-model="inputPriceVal" 
                        v-model:stateError="errorInputs.inputPriceVal" 
                        placeholder="Votre montant"
                        type="text"
                        id="input-amount-trs"
                        validFormat="amount"
                        :hideAnimation="true"
                        :onlyNumbers="true"
                    />
                </div>
            </div>

            <div class="w-1/2 flex flex-col mt-4 mb-6">
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
    
    import { computed } from 'vue';
    import InputBase from '@/component/input/InputBase.vue';
    const props = defineProps({
        infoTransaction: { default: []},
        typeTransaction: { default: false}
    })
    // const textNote = computed(() => {
    //     if(props.infoTransaction) {
    //         const isPurchase = props.typeTransaction === false;
    //         return (isPurchase) ? "Note" : "Note";
    //     }
    //     else {
    //         const isPurchase = props.infoTransaction?.transaction_type === 'purchase';
    //         return (isPurchase) ? "Note" : "Note";
    //     }
    // });

    // const textAmount = computed(() => {
    //     if(props.infoTransaction) {
    //         const isPurchase = props.typeTransaction === false;
    //         return (isPurchase) ? "Montant (€)" : "Montant de prélèvement (€)";
    //     }
    //     else {
    //         const isPurchase = props.infoTransaction?.transaction_type === 'purchase';
    //         return (isPurchase) ? "Montant (€)" : "Montant de prélèvement (€)";
    //     }
    // });
    
    const inputNoteVal = defineModel('inputNoteVal');
    const inputPriceVal = defineModel('inputPriceVal');
    const inputDateVal = defineModel('inputDateVal');
    const errorInputs = defineModel('errorInputs');
</script>