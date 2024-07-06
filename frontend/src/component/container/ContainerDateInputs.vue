<template>
    <div class="flex gap-[10%] w-[30%]">
        <div class="w-[40%]">
            <div class="flex gap-3">
                <InputCheckbox 
                v-model="checkboxDateMin" 
                width="w-fit"
                extraClass="border-b-2 font-light" 
                id="checkbox-date"
                type="checkbox"/>
                <label class="text-gray-300 font-light" for="checkbox-date">Depuis le</label>
            </div>                        
            <!-- <p>Plage de date </p> -->
            <div :class="{'opacity-50': !checkboxDateMin }">
                <div class="">
                    <InputBase 
                    v-model="dateStart" 
                    width="w-fit"
                    extraClass="text-[18px] py-2 border-b-2 w-full text-white font-light mt-[2px]" 
                    placeholder="search"
                    id="input-date"
                    :disabled="!checkboxDateMin"
                    type="date"/>
                </div>
                </div>
        </div>

        <div class="w-[40%]">
            <div class="flex gap-3">
                <InputCheckbox 
                v-model="checkboxDateMax" 
                width="w-fit"
                extraClass="border-b-2 font-light" 
                id="checkbox-date"
                type="checkbox"/>
                <label class="text-gray-300 font-light" for="checkbox-date">Jusqu'au</label>
            </div>                        
            <!-- <p>Plage de date </p> -->
            <div :class="{'opacity-50': !checkboxDateMax }">
                <div>
                    <InputBase 
                    v-model="dateEnd" 
                    width="w-fit"
                    extraClass="text-[18px] py-2 border-b-2 w-full text-white font-light mt-[2px]" 
                    placeholder="search"
                    id="input-date"
                    :disabled="!checkboxDateMax"
                    type="date"/>
                </div>
                </div>
        </div>                      
    </div>
</template>


<script setup>
    import InputBase from '@/component/input/InputBase.vue';
    import InputCheckbox from '@/component/input/InputCheckbox.vue';

    const checkboxDateMin = defineModel('checkboxDateMin');
    const checkboxDateMax = defineModel('checkboxDateMax');
    const dateStart = defineModel('dateStart');
    const dateEnd = defineModel('dateEnd');
    
</script>