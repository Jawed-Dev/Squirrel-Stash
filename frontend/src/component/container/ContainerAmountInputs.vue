<template>
    <div class="flex gap-[10vw] w-[40%]">

        <div class="w-[50%]">
            <label class="flex text-[18px] text-white font-light" for="login-pass">Montant minimum</label>
            <InputBase 
                unicode="🔍"
                v-model="searchAmountMin" 
                width="w-fit"
                extraClass="" 
                placeholder="Ex : 100"
                id="input-date"
                type="text"
            />
        </div>

        <div class="w-[50%]">
            <label class="flex text-[18px] text-white font-light" for="login-pass">Montant maximum</label>
            <InputBase 
                unicode="🔍"
                v-model="searchAmountMax" 
                width="w-fit"
                extraClass="" 
                placeholder="Ex : 200"
                id="input-date"
                type="text"
            />
        </div>

    </div>
</template>

<script setup>
    import InputBase from '@/component/input/InputBase.vue';

    // props, variables ...
    const searchAmountMin = defineModel('searchAmountMin');
    const searchAmountMax = defineModel('searchAmountMax');
</script>