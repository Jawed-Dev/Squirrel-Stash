<template>
    <div class="flex flex-col w-1/3 min-w-[270px] lg:min-w-[280px] 2xl:min-w-[300px] md:w-1/4">
        <label class="pl-2 flex text-white font-light" for="input-note">Note</label>
        <InputBase 
            iconName="Pencil"
            v-model="searchNote"
            placeholder="Ex : Cadeau"
            id="input-note"
            type="text"
            :hideAnimation="true"
        />
    </div>
</template>

<script setup>
    import InputBase from '@/components/input/InputBase.vue';

    // props, variables ...
    const searchNote = defineModel();
</script>