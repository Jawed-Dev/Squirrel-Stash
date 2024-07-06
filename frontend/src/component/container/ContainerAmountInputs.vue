<template>
    <div class="w-[30%]">
        <div class="flex gap-[10%] ">
            <div class="w-[40%]">
                <label class="flex text-[20px] text-white font-light" for="login-pass">Montant minimum</label>
                <InputBase 
                v-model="searchAmountMin" 
                width="w-fit"
                extraClass="w-[20%] border-b-2 w-full py-1 text-white font-light mt-[2px]" 
                placeholder="Montant"
                id="input-date"
                type="text"/>
            </div>
    
            <div class="w-[40%]">
                <label class="flex text-[20px] text-white font-light" for="login-pass">Montant maximum</label>
                <InputBase 
                v-model="searchAmountMax" 
                width="w-fit"
                extraClass="border-b-2 w-full py-1 text-white font-light mt-[2px]" 
                placeholder="Montant"
                id="input-date"
                type="text"/>
            </div>
        </div>
    </div>
</template>

<script setup>
    import InputBase from '@/component/input/InputBase.vue';

    // props, variables ...
    const searchAmountMin = defineModel('searchAmountMin');
    const searchAmountMax = defineModel('searchAmountMax');
</script>