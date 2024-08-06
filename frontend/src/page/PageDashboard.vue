<template>
    <div class="font-main-font flex flex-col bg-main-bg w-full pb-[calc(50px)] md:pb-0">
        <div class="mx-1 md:ml-[calc(20px+65px+20px)] xl:ml-[calc(30px+75px+30px)] md:mr-[20px] xl:mr-[30px] flex flex-col mt-5">
            <h1 class="text-2xl font-light text-white">Économie du mois</h1>
            <p class="font-light text-white mt-2 pr-2">Bonjour {{ firstNameUser }}, voici votre résumé du mois.</p> 

            <div class="flex gap-5 flex-col lg:flex-row lg:justify-between items-center w-full">
                <section class="flex w-full flex-col justify-between lg:mt-5 order-2 lg:order-1">
                    <div class="flex flex-col gap-5 sm:gap-2 sm:flex-row sm:w-full lg:w-1/4 lg:mb-0">
                        <SelectMonth class="min-h-[42px] w-full md:min-w-[200px]" v-model="dateSelected.month" :listSelect="monthNames" />
                        <SelectYear class="min-h-[42px] w-full sm:min-w-[200px]" v-model="dateSelected.year" :listSelect="getAvailableYear()" />
                    </div>
                    <AddTransaction/>   
                </section>

                <ContainerStatMonth 
                    class="order-1 lg:order-2"
                    :isIconActive="true" nameIcon="target" bgIcon="bg-gradient-blue" 
                    :colorValue="'text-white'" 
                    :amountValue="threshold.amount +' €'" 
                    :nameStat="'Seuil mensuel'" 
                    :width="'mt-5 w-full lg:min-w-[calc(200px*2+8px)] lg:w-1/4'"
                />
    
            </div>

            <section class="w-full mt-custom-margin-main rounded-[3px] overflow-hidden shadow-black shadow-custom-main"> 
                <ContainerTransactionsMonth />
            </section>

            <section class="flex flex-col sm:flex-row sm:gap-5 justify-around">

                <div class="flex flex-col w-full justify-around min-[1340px]:flex-row min-[1340px]:gap-5 ">
                    <ContainerStatMonth nameIcon="calculator" bgIcon="bg-gradient-orange" :colorValue="'text-custom-orange'" 
                    :amountValue="statisticDetails.totalTransactions + ' €'" :nameStat="'Total des transactions'" :width="'min-[1340px]:w-1/2 w-full'" />
    
                    <ContainerStatMonth nameIcon="balance" bgIcon="bg-gradient-green" :colorValue="'text-custom-green'" 
                    :amountValue="filterTextBalanceEconomy" :nameStat="'Balance d\'économie'" :width="'min-[1340px]:w-1/2 w-full'" />
                </div>
                
                <div class="flex flex-col w-full justify-around min-[1340px]:flex-row min-[1340px]:gap-5">
                    <ContainerStatMonth :nameIcon="iconNamePurchases" bgIcon="bg-gradient-blue" :colorValue="'text-white'" 
                    :amountValue="nameBiggestPurchase" :nameStat="'Plus gros achat / Catégorie'" :width="'min-[1340px]:w-1/2 w-full'" />
                    
                    <ContainerStatMonth :nameIcon="iconNameRecurrings" bgIcon="bg-gradient-vanusa" :colorValue="'text-white'"  
                    :amountValue="nameBiggestRecurring" :nameStat="'Plus gros prélèvement / Catégorie'" :width="'min-[1340px]:w-1/2 w-full'" />
                </div>
            </section>

            <section class="flex xl:gap-5 flex-col xl:flex-row justify-between ">
                <ContainerListTransactions v-model="topTransactions.typeTrsPurchases" class="w-full h-fit" />
                <ContainerListTransactions v-model="topTransactions.typeTrsRecurrings" class="w-full hidden xl:flex h-fit" />
            </section>
        </div>
    </div>
</template>


<script setup>
    import { ref, watch, computed, onMounted, reactive } from 'vue'; 
    import ContainerStatMonth from '@/component/container/ContainerStatMonth.vue';
    import SelectYear from '@/component/select/SelectYear.vue';
    import SelectMonth from '@/component/select/SelectMonth.vue';
    import ContainerTransactionsMonth from '@/component/container/ContainerTransactionsMonth.vue';
    import ContainerListTransactions from '@/component/container/ContainerListTransactions.vue';
    import AddTransaction from '@/component/overlay/AddTransaction.vue';
    import { monthNames, getAvailableYear, getCurrentMonthName, getCurrentYear, getMonthNumber } from '@/composable/useGetDate';
    import { getUserFirstName } from '@/composable/useBackendGetData';
    import { storeThreshold, storeDateSelected, storeStatisticDetails } from '@/storePinia/useStoreDashboard';
    import { updateThresholdByMonth, updateTotalTrsByMonth, updateBalanceEcoByMonth, updateBiggestTrsByMonth } from '@/storePinia/useUpdateStoreByBackend';
    


    // stores Pinia
    const threshold = storeThreshold();
    const dateSelected = storeDateSelected();
    const statisticDetails = storeStatisticDetails();
    const firstNameUser = ref('');
    
    const topTransactions = reactive({
        typeTrsPurchases: false,
        typeTrsRecurrings: true
    });

    // life cycle / functions
    onMounted(async () => {
        const response = await getUserFirstName();
        const userFirstName = response?.data?.user_first_name;
        firstNameUser.value = userFirstName;

        const currentMonthName = getCurrentMonthName();
        const currentYear = getCurrentYear();
        const currentMonthNumber = getMonthNumber(currentMonthName);
        dateSelected.month = currentMonthNumber;
        dateSelected.year = currentYear;
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
        const nameIcon = statisticDetails?.biggestPurchase?.transaction_category;
        return (nameIcon) ? nameIcon : 'Invisible';
    });
    const iconNameRecurrings = computed(() => {
        const nameIcon = statisticDetails?.biggestRecurring?.transaction_category;
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
</script>