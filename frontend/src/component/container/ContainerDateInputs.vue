<template>
    <div class="flex gap-[10vw] w-[40%]">
        
        <div class="w-[50%]">
            <label class="text-white text-[18px] font-light" for="input-date-start">Depuis la date du : </label>                    
                <div class="flex">
                    <InputBase 
                        unicode="🔍"
                        v-model="dateStart" 
                        width="w-full"
                        extraClass="text-[15px]" 
                        id="input-date-start"
                        placeholder="Ex: 06/06/2024"
                        type="date"
                    />
                </div>
        </div>

        <div class="w-[50%]">
            <label class="text-white text-[18px] font-light" for="input-date-end">Jusqu'à la date du :</label>                     
                <div>
                    <InputBase 
                        unicode="🔍"
                        v-model="dateEnd" 
                        width="w-full"
                        extraClass="text-[15px]" 
                        placeholder="Ex: 06/06/2024"
                        id="input-date-end"
                        type="date"
                    />
                </div>
        </div>                      
    </div>
</template>


<script setup>
    import InputBase from '@/component/input/InputBase.vue';

    const checkboxDateMin = defineModel('checkboxDateMin');
    const checkboxDateMax = defineModel('checkboxDateMax');
    const dateStart = defineModel('dateStart');
    const dateEnd = defineModel('dateEnd');
    
</script>