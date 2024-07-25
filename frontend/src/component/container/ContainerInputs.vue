<template>
    <div class="flex">
        <!-- dépenses valeur -->
        <div class="flex items-center flex-col w-full border-b-[1px] border-gray-700 gap-3 py-8">
            <div class="flex justify-center sm:justify-end w-full">
                <div class="w-[55%] sm:w-1/3 mr-1">
                    <InputBase 
                        v-model="inputDateVal" 
                        v-model:stateError="errorInputs.inputDateVal" 
                        width="w-full"
                        placeholder="Date"
                        id="input-date"
                        type="date"
                        validFormat="trsDate"
                        :hideAnimation="true"
                        extraClass="text-[14px] py-[2px]"
                    />
                </div>
            </div>

            <div class="w-[55%] sm:w-[45%]">
                <div class="w-full">
                    <label for="input-amount-trs" class="text-lg flex justify-center font-light">Montant *</label>
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

            <div class="w-[55%] sm:w-[45%] flex flex-col">
                <!-- placeholder note -->
                <div class="w-full">
                    <label for="input-note-trs" class="px-2 text-lg flex justify-center font-light overflow-x-hidden text-ellipsis">{{textNote}}</label>
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
    const textNote = computed(() => {
        if(props.infoTransaction) {
            const isPurchase = props.typeTransaction === false;
            return (isPurchase) ? "Note" : "Note";
        }
        else {
            const isPurchase = props.infoTransaction?.transaction_type === 'purchase';
            return (isPurchase) ? "Note" : "Note";
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