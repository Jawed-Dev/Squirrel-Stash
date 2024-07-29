<template>

    <div class="font-main-font flex flex-col bg-main-bg w-full pb-[calc(50px)] md:pb-0">
        <div class="mx-1 md:ml-[calc(20px+65px+20px)] md:mr-custom-margin-main flex flex-col mt-[20px]">
            <h1 class="text-[25px] font-light text-white">Économie du mois</h1>
            <p class="font-light text-white mt-2 pr-2">Bonjour {{ firstNameUser }}, voici votre résumé du mois.</p> 

            <ContainerStatMonth 
                :isIconActive="true" nameIcon="target" bgIcon="bg-gradient-blue" :svg="svgConfig('target', 'bg-gradient-blue')" 
                :colorValue="'text-white'" 
                :amountValue="threshold.amount +' €'" 
                :nameStat="'Seuil mensuel'" 
                :width="'md:w-1/4 md:min-w-[calc(197px*2+8px)] sm:w-full'"
            />

            <section class="flex flex-col md:flex-row justify-between pt-5">
                <div class="flex flex-col gap-5 sm:gap-2 sm:flex-row md:w-1/4 sm:w-full">
                    <SelectMonth class="min-h-[42px] w-full sm:min-w-[197px]" v-model="dateSelected.month" :listSelect="monthNames" />
                    <SelectYear class="min-h-[42px] w-full sm:min-w-[197px]" v-model="dateSelected.year" :listSelect="getAvailableYear()" />
                </div>
                <AddTransaction class="" width="sm:w-1/4 sm:min-w-[450px] w-full max-w-[400px]"/>   
            </section>

            <section class="w-[100%] mt-custom-margin-main rounded-[3px] overflow-hidden shadow-black shadow-custom-main"> 
                <ContainerTransactionsMonth />
            </section>

            <section class="flex flex-col sm:flex-row sm:gap-5 justify-around">

                <div class="flex flex-col w-full justify-around min-[1300px]:flex-row min-[1300px]:gap-5 ">
                    <ContainerStatMonth nameIcon="calculator" bgIcon="bg-gradient-orange" :colorValue="'text-custom-orange'" 
                    :amountValue="statisticDetails.totalTransactions + ' €'" :nameStat="'Total des transactions'" :width="'min-[1300px]:w-1/2 w-full'" />
    
                    <ContainerStatMonth nameIcon="balance" bgIcon="bg-gradient-green" :colorValue="'text-custom-green'" 
                    :amountValue="filterTextBalanceEconomy" :nameStat="'Balance d\'économie'" :width="'min-[1300px]:w-1/2 w-full'" />
                </div>
                
                <div class="flex flex-col w-full justify-around min-[1300px]:flex-row min-[1300px]:gap-5 ">
                    <ContainerStatMonth :nameIcon="iconNamePurchases" bgIcon="bg-gradient-blue" :colorValue="'text-white'" 
                    :amountValue="nameBiggestPurchase" :nameStat="'Plus gros achat / Catégorie'" :width="'min-[1300px]:w-1/2 w-full'" />
                    
                    <ContainerStatMonth :nameIcon="iconNameRecurrings" bgIcon="bg-gradient-vanusa" :colorValue="'text-white'"  
                    :amountValue="nameBiggestRecurring" :nameStat="'Plus gros prélèvement / Catégorie'" :width="'min-[1300px]:w-1/2 w-full'" />
                </div>
            </section>

            <section class="flex justify-between ">
                <ContainerListTransactions class="w-full" />
            </section>

            <!-- <TransitionPopUp duration-in="500" duration-out="500">
                <OverlaySuccessAction redirect="login" text="Votre compte a bien été créé." v-if="overlayActive" v-model:overlayActive="overlayActive" />
            </TransitionPopUp> -->
        </div>
    </div>
</template>


<script setup>
    import { ref, watch, computed, onMounted } from 'vue'; 
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
    
    // stores Pinia
    const threshold = storeThreshold();
    const dateSelected = storeDateSelected();
    const statisticDetails = storeStatisticDetails();
    const firstNameUser = ref('');

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