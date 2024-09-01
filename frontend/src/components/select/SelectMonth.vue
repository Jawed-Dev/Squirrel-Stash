<template>
    <select @change="handleSelect($event)" 
    class="w-1/2 pl-2 gradient-border text-white rounded-[3px] bg-main-gradient 
     shadow-main focus:outline-none font-light cursor-pointer hover:shadow-custom-lower" 
    name="month" id="month-select">
        <option class="bg-main-bg font-light" 
            v-for="(textSelect, index) in listSelect" 
            :selected="index + 1 === indexSelected" 
            :key="index" 
            :value="index">{{ textSelect }}
        </option>
    </select>
</template>

<script setup>
    import { isValidMonth } from '@/errors/useValidFormat';

    const props = defineProps({
        listSelect: { default: '' }
    });
    const indexSelected = defineModel();
    
    function handleSelect(event) {
        let monthNumber = Number(event.target.value)+1;
        monthNumber = (isValidMonth(monthNumber)) ? monthNumber : 0;
        indexSelected.value = monthNumber;
    }

</script>