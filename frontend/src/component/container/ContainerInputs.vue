<template>
    <div class="flex">
        <!-- dépenses valeur -->
        <div class="flex items-center flex-col w-full border-b-[1px] border-gray-700 py-[20px]">
            <div class="flex justify-end items-center w-full pr-3">
                <div class="flex items-center justify-center mt-[5px]">
                    <InputBase 
                        v-model="inputDateVal" 
                        v-model:stateError="errorInputs.inputDateVal" 
                        width="w-full"
                        placeholder="Date"
                        id="input-date"
                        type="date"
                        :borderHidden="true"
                        validFormat="date"
                        :hideAnimation="true"
                    />
                </div>
            </div>

            <div class="w-1/3 flex flex-col items-center mt-3">
                <div class="w-full">
                    <label for="input-amount-trs" class="flex justify-center font-light">Montant (€)</label>
                    <InputBase 
                        unicode="💵"
                        v-model="inputPriceVal" 
                        v-model:stateError="errorInputs.inputPriceVal" 
                        placeholder="Montant"
                        type="text"
                        id="input-amount-trs"
                        validFormat="amount"
                    />
                </div>
            </div>

            <div class="w-1/2 flex flex-col mt-7 mb-3">
                <!-- placeholder notes -->
                <div class="w-full">
                    <label for="input-note-trs" class="flex justify-center font-light">{{textNote}}</label>
                    <InputBase 
                        v-model="inputNoteVal"
                        v-model:stateError="errorInputs.inputNoteVal" 
                        unicode="✏️"
                        type="text"
                        placeholder="Note"
                        id="input-note-trs"
                        validFormat="note"
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
    const textNote = computed(() => {
        if(props.infoTransaction) {
            const isPurchase = props.typeTransaction === false;
            return (isPurchase) ? "Note d'achat" : "Note de prélèvement";
        }
        else {
            const isPurchase = props.infoTransaction?.transaction_type === 'purchase';
            return (isPurchase) ? "Note d'achat" : "Note de prélèvement";
        }
    });
    const textAmount = computed(() => {
        if(props.infoTransaction) {
            const isPurchase = props.typeTransaction === false;
            return (isPurchase) ? "Montant (€)" : "Montant de prélèvement (€)";
        }
        else {
            const isPurchase = props.infoTransaction?.transaction_type === 'purchase';
            return (isPurchase) ? "Montant (€)" : "Montant de prélèvement (€)";
        }
    });
    const inputNoteVal = defineModel('inputNoteVal');
    const inputPriceVal = defineModel('inputPriceVal');
    const inputDateVal = defineModel('inputDateVal');
    const errorInputs = defineModel('errorInputs');
</script>