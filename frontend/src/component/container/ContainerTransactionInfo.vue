<template>
    <div>
        <div v-if="props.infoTransaction.transaction_id" :class="`py-8 flex flex-col border-gray-700 text-white ${borderBottom} `">

            <div :class="`pl-1 sm:pl-4 md:pl-3 font-light flex items-center`">
                <IconLoader 
                    :nameIcon="infoTransaction.transaction_category" 
                    :svg="iconConfig" 
                    :class="`${iconConfig.color} rounded-full p-[10px]  shadow-main`"
                />
        
                <p class="w-[25%] text-[15px] pl-[5px] md:pl-[20px] sm:text-left 
                overflow-hidden text-ellipsis">{{infoTransaction.transaction_category}}</p>
                <p class="w-[22%] text-[15px] pl-5 sm:pl-0 text-left ">{{ transactionAmount }}</p>
                <p class="w-[22%] text-[15px] sm:text-left text-center">{{infoTransaction.formatted_date}}</p>
                <p class="text-[15px] grow text-center md:pl-[1px] sm:w-[14%] sm:grow-0 sm:text-left ">{{ transactionCount }}</p>
    
                <div class="flex items-center flex-col gap-1 pr-3 sm:grow overflow-visible">
                    <EditDeleteTransaction 
                        :infoTransaction="infoTransaction" 
                        :indexMenu="props.indexMenu" 
                        v-model:currentMenuEditDeleteTrs="currentMenuEditDeleteTrs" 
                    />
                    <IconInfo 
                        @click="toggleDetails"
                        class="cursor-pointer trigger-info-note"
                        :svg="styleIconInfo"
                    />
                </div>

                
            </div>
            <TransitionOpacity duration-out="duration-300">
                <div v-if="isShowingDetails" class="relative flex items-center trigger-info-note">
                    <p v-if="infoTransaction.transaction_note" 
                        class="text-md text-center pl-4 py-[3px] absolute w-full bg-main-gradient font-light top-[2px] shadow-main" >
                        Note : <span class="font-medium">{{ infoTransaction.transaction_note }}</span>
                    </p>
                    <p  v-else class="text-md text-center pl-4 py-[3px] absolute w-full bg-main-gradient font-light top-[2px] shadow-main">
                        Aucune note
                    </p>
                </div>
            </TransitionOpacity>
            
            
        </div>
        
        <div 
            v-if="(!props.infoTransaction.transaction_id)" 
            :class="`min-h-[124px] sm:pl-4 md:pl-3 flex items-center py-6 ${borderBottom} border-gray-700 text-white relative`">
            <IconLoader nameIcon="Invisible" :svg="iconConfig" 
            :class="`${iconConfig.color} rounded-full p-[10px]  shadow-main`"/>
            <p class="absolute w-full text-[15px] text-center font-light">Aucune donnée</p>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed } from 'vue';
    import IconLoader from '@/composable/useIconLoader.vue';
    import EditDeleteTransaction from '@/component/overlay/EditDeleteTransaction.vue';    
    import IconInfo from '@/component/svgs/IconInfo.vue';
    import { setSvgConfig } from '@/svg/svgConfig';
    import TransitionOpacity from '@/component//transition/TransitionOpacity.vue';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';
    
    
    
    // variables, props...
    const props = defineProps({
        infoTransaction: { default: {} },
        indexMenu: { default: 0 },
        lengthData: { default: 0 }
    });

    const styleIconInfo = setSvgConfig({width:'25px', fill:'white'})
    const currentMenuEditDeleteTrs = defineModel('currentMenuEditDeleteTrs');
    const isShowingDetails = ref(false);

    // life cycle, functions
    const transactionAmount = computed(() => {
       return (props.infoTransaction.transaction_id) ? props.infoTransaction.transaction_amount + ' €' : '';
    });
    const transactionCount = computed(() => {
       return (props.infoTransaction.transaction_id) ? props.infoTransaction.count_categories + ' x' : '';
    });
    const iconConfig = computed(() => {
        return (props.infoTransaction.transaction_type === 'purchase') ? 
        svgConfig('bg-gradient-blue','40px') : svgConfig('bg-gradient-vanusa','40px');
    });
    const borderBottom = computed(() => {
        return (props.indexMenu === props.lengthData -1) ? '' : 'border-b';
    });
    
    useClickOutside('.trigger-info-note', isShowingDetails, () => {
        isShowingDetails.value = false;
    });

    useEscapeKey(isShowingDetails, () => {
        isShowingDetails.value = false;
    });
    
    function toggleDetails() {
        isShowingDetails.value = !isShowingDetails.value;
    }

    function svgConfig(color, width = '20px') {
        return {
            color: color,
            width: width,
            height: width,
            fill: 'white'
        }
    }
</script>