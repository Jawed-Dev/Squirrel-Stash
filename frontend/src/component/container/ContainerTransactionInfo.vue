<template>
    <div v-if="props.infoTransaction.transaction_id" :class="`pl-3 font-light flex ${borderBottom} items-center py-6 border-gray-700 text-white`">
        <IconLoader 
            :nameIcon="infoTransaction.transaction_category" 
            :svg="iconConfig" 
            :class="`${iconConfig.color} rounded-full p-[0.7vw] shadow-black shadow-custom-main`"
        />

        <p class="w-[20%] pl-[15px] text-[15px]">{{infoTransaction.transaction_category}}</p>
        <p class="w-[20%] text-[15px]">{{ transactionAmount }}</p>
        <p class="w-[20%] text-[15px]">{{infoTransaction.formatted_date}}</p>
        <p class="w-[15%] text-[15px]">{{ transactionCount }}</p>
        <EditDeleteTransaction :infoTransaction="infoTransaction" 
        :indexMenu="props.indexMenu" 
        v-model:currentMenuEditDeleteTrs="currentMenuEditDeleteTrs" />
    </div>
    <div v-if="(!props.infoTransaction.transaction_id)" :class="`pl-3 flex items-center py-6 ${borderBottom} border-gray-700 text-white relative`">
        <IconLoader nameIcon="Invisible" :svg="iconConfig" :class="`${iconConfig.color} rounded-full p-[1.5%] shadow-black shadow-custom-main`"/>
        <p class="absolute w-full text-[15px] text-center font-light">Aucune donnée</p>
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

    const transactionAmount = computed(() =>{
       return (props.infoTransaction.transaction_id) ? props.infoTransaction.transaction_amount + ' €' : '';
    });
    const transactionCount = computed(() =>{
       return (props.infoTransaction.transaction_id) ? props.infoTransaction.count_categories + ' x' : '';
    });
    const iconConfig = computed(() =>{
        return (props.infoTransaction.transaction_type === 'purchase') ? svgConfig('bg-gradient-blue','2.5vw') : svgConfig('bg-gradient-vanusa','2.5vw');
    });
    const borderBottom = computed(() =>{
        return (props.indexMenu === props.lengthData -1) ? '' : 'border-b';
    });

    function svgConfig(color, width = '3vw') {
        return {
            color: color,
            width: width,
            height: width,
            fill: 'white'
        }
    }

</script>