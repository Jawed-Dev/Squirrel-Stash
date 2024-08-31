<template>
    <div>
        <div v-if="props.infoTransaction.transaction_id" :class="`py-8 flex flex-col border-gray-700 text-white ${borderBottom}`">

            <div :class="`pl-1 sm:pl-5 font-light flex items-center`">
                
                <IconLoader 
                    :nameIcon="infoTransaction.transaction_category" 
                    :svg="iconConfig" 
                    :class="`${iconConfig.color} hidden sm:flex rounded-full p-2 shadow-main `"
                />
                
                <div class="w-[40%] sm:w-1/2 flex-col flex sm:flex-row">
                    <!-- category -->
                    <p class="w-full sm:w-1/2 text-[15px] pl-4 
                    overflow-hidden text-ellipsis">{{infoTransaction.transaction_category}}</p>
                     <!-- Amount -->
                    <p class="pl-5 sm:pl-0 grow text-nowrap text-left overflow-hidden text-ellipsis text-[15px]">{{ transactionAmount }}</p>
                </div>

                <!-- Date -->
                <p class="w-[30%] sm:w-[22%] text-[15px]">{{infoTransaction.formatted_date}}</p>
                <!-- Iteration -->
                <p class="grow text-[15px] md:pl-[1px] text-nowrap">{{ transactionCount }}</p>
    
            
                <div class="flex items-center flex-col gap-1 pr-8 sm:pr-3 sm:grow overflow-visible">
                    <EditDeleteTransaction 
                        :infoTransaction="infoTransaction" 
                        :indexMenu="props.indexMenu" 
                        v-model:currentMenuEditDeleteTrs="currentMenuEditDeleteTrs" 
                    />
                    <IconInfo 
                        @click="toggleDetails"
                        class="cursor-pointer trigger-info-note hover:stroke-blue-500"
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
            :class="`min-h-[124px] pl-1 sm:pl-5 py-8 flex flex-col border-gray-700 text-white ${borderBottom}`">
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
    import { formatFloatAsString, formatStringToFloat } from '@/composable/useMath';
    
    
    
    
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
        const amount = props.infoTransaction.transaction_amount;
       return (amount) ? formatFloatAsString(amount) + ' €' : '';
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