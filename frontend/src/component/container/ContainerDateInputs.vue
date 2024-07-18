<template>
    <div class="flex flex-col w-[20%]">
        <label class=" text-white font-light" for="input-date-start">Depuis la date du : </label>                    
        <InputBase 
            extraClass="py-[2px]"
            v-model="dateStart" 
            id="input-date-start"
            placeholder="Ex: 06/06/2024"
            type="date"
            :hideAnimation="true"
        />
    </div>

    <div class="flex flex-col w-[20%]">
        <label class="text-white font-light" for="input-date-end">Jusqu'à la date du :</label>                     
        <InputBase 
            extraClass="py-[2px]"
            v-model="dateEnd" 
            placeholder="Ex: 06/06/2024"
            id="input-date-end"
            type="date"
            :hideAnimation="true"
        />
    </div>                      
</template>


<script setup>
    import InputBase from '@/component/input/InputBase.vue';

    const checkboxDateMin = defineModel('checkboxDateMin');
    const checkboxDateMax = defineModel('checkboxDateMax');
    const dateStart = defineModel('dateStart');
    const dateEnd = defineModel('dateEnd');
    
</script>