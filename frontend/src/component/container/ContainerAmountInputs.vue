<template>
    <div class="flex flex-col w-1/3 min-w-[280px] lg:min-w-[300px] 2xl:min-w-[300px] md:w-1/4">
        <label class="pl-2 text-white font-light" for="input-amount-min">Montant minimum</label>
        <InputBase 
            iconName="Amount"
            v-model="searchAmountMin" 
            placeholder="Ex : 100"
            id="input-amount-min"
            type="text"
            :hideAnimation="true"
            :onlyNumbers="true"
        />
    </div>

    <div class="flex flex-col w-1/3 min-w-[280px] lg:min-w-[300px] 2xl:min-w-[300px] md:w-1/4">
        <label class="pl-2 text-white font-light" for="input-amount-max">Montant maximum</label>
        <InputBase 
            iconName="Amount"
            v-model="searchAmountMax" 
            placeholder="Ex : 200"
            id="input-amount-max"
            type="text"
            :hideAnimation="true"
            :onlyNumbers="true"
        />
    </div>
</template>

<script setup>
    import InputBase from '@/component/input/InputBase.vue';
    

    // props, variables ...
    const searchAmountMin = defineModel('searchAmountMin');
    const searchAmountMax = defineModel('searchAmountMax');
</script>