<template>

    <div class="font-main-font flex flex-col bg-main-bg w-full">
        <div class="ml-[calc(20px+70px+20px)] mr-custom-margin-main flex flex-col mt-[20px]">
            <h1 class="text-[25px] font-light text-white">Économie du mois</h1>
            <p class="font-light text-white mt-2">Bonjour {{ firstNameUser }}, voici votre résumé du mois.</p> 

            <ContainerStatMonth 
                :isIconActive="true" :svg="svgConfig('target', 'bg-gradient-blue')" 
                :colorValue="'text-white'" 
                :amountValue="threshold.amount +' €'" 
                :nameStat="'Seuil mensuel'" 
                :width="'w-[30%]'"
            />
            <section class="flex justify-between pt-5">
                <div class="flex gap-[20px] w-[30%]">
                    <SelectMonth v-model="dateSelected.month" :listSelect="monthNames" />
                    <SelectYear v-model="dateSelected.year" :listSelect="getAvailableYear()" />
                </div>
                <div class="flex justify-end">
                    <AddTransaction width="w-[30%]"/>
                </div>
            </section>

            <section class="w-[100%] mt-custom-margin-main rounded-[3px] overflow-hidden shadow-black shadow-custom-main"> 
                <ContainerTransactionsMonth />
            </section>

            <section class="flex gap-[20px] justify-around ">
                <ContainerStatMonth :svg="svgConfig('calculator', 'bg-gradient-orange')" :colorValue="'text-custom-orange'" 
                :amountValue="statisticDetails.totalTransactions + ' €'" :nameStat="'Total des transactions'" :width="'w-[25%]'" />

                <ContainerStatMonth :svg="svgConfig('balance', 'bg-gradient-green')" :colorValue="'text-custom-green'" 
                :amountValue="filterTextBalanceEconomy" :nameStat="'Balance d\'économie'" :width="'w-[25%]'" />
                
                <ContainerStatMonth :svg="svgConfig(iconNamePurchases, 'bg-gradient-blue')" :colorValue="'text-white'" 
                :amountValue="nameBiggestPurchase" :nameStat="'Plus gros achat / Catégorie'" :width="'w-[25%]'" />
                
                <ContainerStatMonth :svg="svgConfig(iconNameRecurrings, 'bg-gradient-vanusa')" :colorValue="'text-white'"  
                :amountValue="nameBiggestRecurring" :nameStat="'Plus gros prélèvement / Catégorie'" :width="'w-[25%]'" />
            </section>

            <section class="flex justify-between ">
                <ContainerListTransactions class="w-[calc(50%-10px)]" :title="'Derniers achats'" :componentType="'purchase'"  :svg="svgConfig('restaurant', 'bg-gradient-blue', '6%')" />
                <ContainerListTransactions class=" w-[calc(50%-10px)]" :title="'Derniers prélèvements'" :componentType="'recurring'" :svg="svgConfig('balance', 'bg-gradient-vanusa', '6%')" />
            </section>

            <!-- <TransitionPopUp duration-in="500" duration-out="500">
                <OverlaySuccessAction redirect="login" text="Votre compte a bien été créé." v-if="overlayActive" v-model:overlayActive="overlayActive" />
            </TransitionPopUp> -->
        </div>
    </div>
</template>


<script setup>

    import { ref, watch, computed, onMounted, defineAsyncComponent } from 'vue'; 
    import ContainerStatMonth from '@/component/container/ContainerStatMonth.vue';
    import SelectYear from '@/component/select/SelectYear.vue';
    import SelectMonth from '@/component/select/SelectMonth.vue';
    import ContainerTransactionsMonth from '@/component/container/ContainerTransactionsMonth.vue';
    import ContainerListTransactions from '@/component/container/ContainerListTransactions.vue';
    import AddTransaction from '@/component/overlay/AddTransaction.vue';
    import { monthNames, getAvailableYear } from '@/composable/useGetDate';
    import { getUserFirstName } from '@/composable/useBackendGetData';
    import { storeThreshold, storeDateSelected, storeStatisticDetails } from '@/storePinia/useStoreDashboard';
    import { updateThresholdByMonth, updateTotalTrsByMonth, updateBalanceEcoByMonth, updateBiggestTrsByMonth } from '@/storePinia/useUpdateStoreByBackend';
    

    // import TransitionPopUp from '@/component/transition/TransitionPopUp.vue';
    // const OverlaySuccessAction = defineAsyncComponent(() => import('@/component/overlay/OverlaySuccessAction.vue'));

    // const overlayActive = ref(true);
    

    // stores Pinia
    const threshold = storeThreshold();
    const dateSelected = storeDateSelected();
    const statisticDetails = storeStatisticDetails();
    const firstNameUser = ref('');

    // variables, props, ...
    
    // life cycle / functions
    onMounted(async () => {
        const response = await getUserFirstName();
        const userFirstName = response?.data?.user_first_name;
        firstNameUser.value = userFirstName;
    });

    watch( () => [dateSelected.month, dateSelected.year], async ([newMonth, newYear]) => {
        updateThresholdByMonth(newMonth, newYear);
        updateTotalTrsByMonth(newMonth, newYear);
        updateBalanceEcoByMonth(newMonth, newYear);
        updateBiggestTrsByMonth(newMonth, newYear, 'purchase');
        updateBiggestTrsByMonth(newMonth, newYear, 'recurring');
    }, {  immediate:true, deep:true });

    // computed
    const filterTextBalanceEconomy = computed(() => {
        if((statisticDetails.economyBalance === 0)) return '0 €';
        else if((statisticDetails.economyBalance > 0)) return '+'+ statisticDetails.economyBalance + ' €';
        else return statisticDetails.economyBalance +' €';
    });

    const iconNamePurchases = computed(() => {
        console.log(statisticDetails);
        const nameIcon = statisticDetails?.biggestPurchase?.transaction_category;
        return (nameIcon) ? nameIcon : 'Invisible';
    });
    const iconNameRecurrings = computed(() => {
        const nameIcon = statisticDetails?.biggestRecurring?.transaction_category;
        //alert(nameIcon);
        return (nameIcon) ? nameIcon : 'Invisible';
    });
    const nameBiggestPurchase = computed(() => {
        const nameBiggestPurch = statisticDetails?.biggestPurchase?.transaction_category;
        return (nameBiggestPurch) ? nameBiggestPurch : 'Aucune donnée';
    });
    const nameBiggestRecurring = computed(() => {
        const nameBiggestPurch = statisticDetails?.biggestRecurring?.transaction_category;
        return (nameBiggestPurch) ? nameBiggestPurch : 'Aucune donnée';
    });

    // functions
    function svgConfig(nameSvg, color, width = '3vw') {
        return {
            name: nameSvg,
            color: color,
            width: width,
            height: width,
            fill: 'white'
        }
    }

</script>