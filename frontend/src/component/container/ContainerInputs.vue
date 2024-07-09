<template>
    <div class="flex">
        <!-- dépenses valeur -->
        <div class="flex flex-col w-full border-b-[1px] border-gray-700 px-1 py-[20px]">

            <div class="w-full flex justify-end items-center pr-3">
                <div class="flex items-center justify-center mt-[5px]">
                    <InputBase 
                        v-model="inputDateVal" 
                        width="w-full"
                        extraClass="text-[16px] border-hidden" 
                        placeholder="Date"
                        id="input-date"
                        type="date"
                        :borderHidden="true"
                    />
                </div>
            </div>

            <div class="flex flex-col items-center mt-[10px] w-full">
                <div class="">
                    <label for="input-amount-trs" class="flex justify-center text-[16px] font-light">{{textAmount}}</label>
                    <InputBase 
                        unicode="💵"
                        v-model="inputPriceVal" 
                        extraClass="text-[16px]" 
                        placeholder="Montant"
                        type="text"
                        id="input-amount-trs"
                    />
                </div>
            </div>

            <div class="flex flex-col px-[25%] gap-2 justify-center mt-[25px] w-full">
                <!-- placeholder notes -->
                <div class="">
                    <label for="input-note-trs" class="flex justify-center text-[16px] font-light">{{textNote}}</label>
                    <InputBase v-model="inputNoteVal"
                        unicode="✏️"
                        extraClass="text-[16px]"
                        type="text"
                        placeholder="Note"
                        id="input-note-trs"
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
            return (isPurchase) ? "Montant d'achat (€)" : "Montant de prélèvement (€)";
        }
        else {
            const isPurchase = props.infoTransaction?.transaction_type === 'purchase';
            return (isPurchase) ? "Montant d'achat (€)" : "Montant de prélèvement (€)";
        }
    });
    const inputNoteVal = defineModel('inputNoteVal');
    const inputPriceVal = defineModel('inputPriceVal');
    const inputDateVal = defineModel('inputDateVal');
</script>