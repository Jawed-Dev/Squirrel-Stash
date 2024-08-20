<template>
    <div class="flex flex-col w-1/3 min-w-[280px] lg:min-w-[300px] 2xl:min-w-[300px] md:w-1/4">
        <label class="pl-2 text-white font-light" for="input-date-start">Depuis la date du : </label>                    
        <InputBase 
            extraClass="py-[2px]"
            v-model="dateStart" 
            id="input-date-start"
            placeholder="Ex: 06/06/2024"
            type="date"
            :hideAnimation="true"
            iconName="Calendar"
        />
    </div>

    <div class="flex flex-col w-1/3 min-w-[280px] lg:min-w-[300px] 2xl:min-w-[300px] md:w-1/4">
        <label class="pl-2 text-white font-light" for="input-date-end">Jusqu'à la date du :</label>                     
        <InputBase 
            extraClass="py-[2px]"
            v-model="dateEnd" 
            placeholder="Ex: 06/06/2024"
            id="input-date-end"
            type="date"
            :hideAnimation="true"
            iconName="Calendar"
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