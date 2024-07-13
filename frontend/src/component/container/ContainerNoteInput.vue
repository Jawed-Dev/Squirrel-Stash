<template>
    <div class="w-[15%]">
        <label class="flex text-[18px] text-white font-light" for="input-note">Note</label>
        <InputBase 
        unicode="🔍"
        v-model="searchNote" 
        width="w-full"
        extraClass="text-[16px]" 
        placeholder="Ex : Cadeau pour Maman"
        id="input-note"
        type="text"/>
    </div>
</template>

<script setup>
    import InputBase from '@/component/input/InputBase.vue';

    // props, variables ...
    const searchNote = defineModel();
</script>