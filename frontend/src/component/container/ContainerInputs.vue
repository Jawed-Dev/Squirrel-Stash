<template>
    <div class="flex">
        <!-- dépenses valeur -->
        <div class="flex items-center flex-col w-full border-b-[1px] border-gray-700 gap-3 py-8">
            <div class="flex justify-end w-full">
                <div class="w-1/3 mr-1">
                    <InputBase 
                        v-model="inputDateVal" 
                        v-model:stateError="errorInputs.inputDateVal" 
                        width="w-full"
                        placeholder="Date"
                        id="input-date"
                        type="date"
                        validFormat="trsDate"
                        :hideAnimation="true"
                        extraClass="text-[15px] py-[2px]"
                    />
                </div>
            </div>

            <div class="w-[45%]">
                <div class="w-full">
                    <label for="input-amount-trs" class="text-lg flex justify-center font-light">Montant *</label>
                    <InputBase 
                        extraClass="text-[15px] py-[2px]"
                        iconName="Amount"
                        v-model="inputPriceVal" 
                        v-model:stateError="errorInputs.inputPriceVal" 
                        placeholder="Montant"
                        type="text"
                        id="input-amount-trs"
                        validFormat="amount"
                        :hideAnimation="true"
                        :onlyNumbers="true"
                        
                        
                    />
                </div>
            </div>

            <div class="w-[45%] flex flex-col">
                <!-- placeholder notes -->
                <div class="w-full">
                    <label for="input-note-trs" class="text-lg flex justify-center font-light">{{textNote}}</label>
                    <InputBase 
                        extraClass="text-[15px] py-[2px]"
                        iconName="Pencil"
                        v-model="inputNoteVal"
                        v-model:stateError="errorInputs.inputNoteVal" 
                        type="text"
                        placeholder="Note"
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