<template>
    <select @change="handleSelect($event)" class="w-[250px] pl-2 gradient-border text-white
    rounded-[3px] bg-main-gradient
    shadow-black shadow-custom-main outline-none font-light" 
    name="month" id="month-select"
    >

    <option v-if="includeEmptyOption" 
            class="bg-main-bg font-light" 
            value=""
            :selected="indexSelected === null">
            {{ emptyOptionText }}
    </option>

    <option class="bg-main-bg font-light" v-for="(textSelect, index) of listSelect" 
        :selected="textSelect === indexSelected" :key="index" :value="textSelect">{{textSelect}}</option>
    </select>

</template>



<script setup>
    const props = defineProps({
        listSelect: { default: '' },
        includeEmptyOption: { type: Boolean, default: false },
        emptyOptionText: { type: String, default: 'Désactivé' } // Texte de l'option vide
    });
    const indexSelected = defineModel();
    
    function handleSelect(event) {
        indexSelected.value = event.target.value;
    }
</script>