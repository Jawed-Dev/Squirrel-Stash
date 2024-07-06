<template>
    <div class="w-[30%]">
        <label class="flex text-[20px] text-white font-light" for="login-pass">Recherche par note</label>
        <InputBase 
        v-model="searchNote" 
        width="w-fit"
        extraClass="border-b-2 w-full py-1 text-white font-light mt-[2px]" 
        placeholder="Note"
        id="input-date"
        type="text"/>
    </div>
</template>

<script setup>
    import InputBase from '@/component/input/InputBase.vue';

    // props, variables ...
    const searchNote = defineModel();
</script>