<template>
    <select @change="handleSelect($event)" class="w-1/2 pl-2 gradient-border text-white
    rounded-[3px] bg-main-gradient cursor-pointer hover:shadow-custom-lower
    shadow-main focus:outline-none font-light" 
    name="month" id="month-select"
    >
    <option class="bg-main-bg font-light" v-for="(textSelect, index) of listSelect" 
    :selected="textSelect === indexSelected" :key="index" :value="textSelect">{{ textSelect }}</option>
    </select>

</template>

<script setup>
    import { getCurrentYear } from '@/composable/useGetDate';
    import { isValidYear } from '@/error/useValidFormat';
    
    const props = defineProps({
        listSelect: { default: '' }
    });
    const indexSelected = defineModel();
    
    function handleSelect(event) {
        indexSelected.value = Number(event.target.value);
        let yearNumber = Number(event.target.value);
        yearNumber = (isValidYear(yearNumber)) ? yearNumber : getCurrentYear();
        indexSelected.value = yearNumber;
    }
</script>