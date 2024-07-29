<template>
    <div class="">
        <div v-if="props.infoTransaction.transaction_id" :class="`pl-[2px] sm:pl-3 font-light flex ${borderBottom} items-center py-6 border-gray-700 text-white`">
            <IconLoader 
                :nameIcon="infoTransaction.transaction_category" 
                :svg="iconConfig" 
                :class="`${iconConfig.color} rounded-full p-[10px] shadow-black shadow-custom-main`"
            />
    
            <p class="w-[25%] text-[15px] pl-[5px] md:pl-[15px] sm:text-left overflow-hidden text-ellipsis">{{infoTransaction.transaction_category}}</p>
            <p class="w-[20%] text-[15px] sm:text-left text-center">{{ transactionAmount }}</p>
            <p class="w-[25%] text-[15px] sm:text-left text-center">{{infoTransaction.formatted_date}}</p>
            <p class="text-[15px] grow text-center md:pl-[1px] sm:w-[14%] sm:grow-0 sm:text-left ">{{ transactionCount }}</p>

            <EditDeleteTransaction 
                :infoTransaction="infoTransaction" 
                :indexMenu="props.indexMenu" 
                v-model:currentMenuEditDeleteTrs="currentMenuEditDeleteTrs" 
                v-model:isSuccessEdit="isSuccessEdit"
                v-model:isSuccessDelete="isSuccessDelete"
            />
        </div>
        <div v-if="(!props.infoTransaction.transaction_id)" :class="`min-h-[89px] pl-[2px] sm:pl-3  flex items-center py-6 ${borderBottom} border-gray-700 text-white relative`">
            <IconLoader nameIcon="Invisible" :svg="iconConfig" 
            :class="`${iconConfig.color} rounded-full p-[10px] shadow-black shadow-custom-main`"/>
            <p class="absolute w-full text-[15px] text-center font-light">Aucune donnée</p>
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue';
    import IconLoader from '@/composable/useIconLoader.vue';
    import EditDeleteTransaction from '@/component/overlay/EditDeleteTransaction.vue';
    
    // variables, props...
    const props = defineProps({
        infoTransaction: { default: {} },
        indexMenu: { default: 0 },
        lengthData: { default: 0 }
    });

    const currentMenuEditDeleteTrs = defineModel('currentMenuEditDeleteTrs');
    const isSuccessEdit = defineModel('isSuccessEdit');
    const isSuccessDelete = defineModel('isSuccessDelete');

    const transactionAmount = computed(() => {
       return (props.infoTransaction.transaction_id) ? props.infoTransaction.transaction_amount + ' €' : '';
    });
    const transactionCount = computed(() => {
       return (props.infoTransaction.transaction_id) ? props.infoTransaction.count_categories + ' x' : '';
    });
    const iconConfig = computed(() => {
        return (props.infoTransaction.transaction_type === 'purchase') ? svgConfig('bg-gradient-blue','40px') : svgConfig('bg-gradient-vanusa','40px');
    });
    const borderBottom = computed(() => {
        return (props.indexMenu === props.lengthData -1) ? '' : 'border-b';
    });
    

    function svgConfig(color, width = '20px') {
        return {
            color: color,
            width: width,
            height: width,
            fill: 'white'
        }
    }

</script>