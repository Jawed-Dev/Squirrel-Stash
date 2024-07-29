<template>
    <div class="flex flex-col w-1/2 min-w-[250px] lg:min-w-[350px] md:w-1/4">
        <label class="flex text-white font-light" for="input-note">Note</label>
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
    import InputBase from '@/component/input/InputBase.vue';

    // props, variables ...
    const searchNote = defineModel();
</script>