<template>
    <div class="font-main flex flex-col bg-main-bg w-full min-h-screen pb-[calc(50px)] md:pb-0">

        <!-- i make a transition for dont show a strange square when spinner is finish -->
        <TransitionOpacity duration-in="300" duration-out="300">
            <ContainerSpinner v-if="!isLoadedData" />
        </TransitionOpacity>

        <div v-show="isLoadedData" class="mx-1 md:ml-[calc(20px+65px+20px)] xl:ml-[calc(30px+75px+30px)] 
            md:mr-[20px] xl:mr-[30px] flex flex-col mt-5">
            <h1 class="text-2xl font-light text-white">Économie du mois</h1>
            <p class="font-light text-white scroll-mt-36 pr-2">Bonjour {{ firstNameUser }}, voici votre résumé du mois.</p> 

            <div class="flex gap-5 flex-col lg:flex-row lg:justify-between items-center w-full">
                <section class="flex w-full flex-col justify-end lg:min-h-[129.5px] order-2 lg:order-1">
                    <div class="flex flex-col gap-5 sm:gap-2 sm:flex-row md:mb-5 sm:w-full lg:w-1/4 lg:mb-5">
                        <SelectMonth class="min-h-[42px] w-full md:min-w-[200px]" v-model="dateSelected.month" :listSelect="monthNames" />
                        <SelectYear class="min-h-[42px] w-full sm:min-w-[200px]" v-model="dateSelected.year" :listSelect="getAvailableYear()" />
                    </div>
                    <AddTransaction/>   
                </section>
                <ContainerStatMonth 
                    class="order-1 lg:order-2"
                    :isIconActive="true" nameIcon="target" bgIcon="bg-gradient-blue" 
                    :colorValue="'text-white'" 
                    :amountValue="textThreshold" 
                    :nameStat="'Seuil mensuel'" 
                    :width="'mt-5 w-full lg:min-w-[calc(200px*2+8px)] lg:w-1/4'"
                />
            </div>

            <ContainerTransactionsMonth />           

            <section class="flex flex-col md:flex-row md:gap-5 justify-around">
                <div class="flex flex-col w-full justify-around 2xl:flex-row 2xl:gap-5">
                    <ContainerStatMonth nameIcon="calculator" bgIcon="bg-gradient-orange" :colorValue="'text-custom-orange'" 
                    :amountValue="textTotalTransaction" :nameStat="'Total des transactions'" :width="'2xl:w-1/2 w-full'" />
    
                    <ContainerStatMonth nameIcon="balance" bgIcon="bg-gradient-green" :colorValue="colorTextBalanceEconomy" 
                    :amountValue="textBalanceEconomy" :nameStat="'Balance d\'économie'" :width="'2xl:w-1/2 w-full'" />
                </div>
                
                <div class="flex flex-col w-full justify-around 2xl:flex-row 2xl:gap-5">
                    <ContainerStatMonth :nameIcon="iconNamePurchases" bgIcon="bg-gradient-blue" :colorValue="'text-white'" 
                    :amountValue="nameBiggestPurchase" :nameStat="'Plus gros achat / Catégorie'" :width="'2xl:w-1/2 w-full'" />
                    
                    <ContainerStatMonth :nameIcon="iconNameRecurrings" bgIcon="bg-gradient-vanusa" :colorValue="'text-white'"  
                    :amountValue="nameBiggestRecurring" :nameStat="'Plus gros prélèvement / Catégorie'" :width="'2xl:w-1/2 w-full'" />
                </div>
            </section>

            <section class="flex 2xl:gap-5 flex-col 2xl:flex-row justify-between">
                <ContainerListTransactions v-model="topTransactions.typeTrsPurchases" class="w-full h-fit" />
                <ContainerListTransactions v-model="topTransactions.typeTrsRecurrings" class="w-full hidden 2xl:flex h-fit" />
            </section>
        </div>
        
    </div>
    
</template>


<script setup>
    import { ref, watch, computed, onMounted, reactive } from 'vue'; 
    import ContainerStatMonth from '@/components/container/ContainerStatMonth.vue';
    import SelectYear from '@/components/select/SelectYear.vue';
    import SelectMonth from '@/components/select/SelectMonth.vue';
    import ContainerTransactionsMonth from '@/components/container/ContainerTransactionsMonth.vue';
    import ContainerListTransactions from '@/components/container/ContainerListTransactions.vue';
    import AddTransaction from '@/components/overlay/AddTransaction.vue';
    import { monthNames, getAvailableYear, getCurrentMonthName, getCurrentYear, getMonthNumber } from '@/composables/useGetDate';
    import { getUserFirstName } from '@/requests/useBackendGetData';
    import { storeThreshold, storeDateSelected, storeStatisticDetails } from '@/storesPinia/useStoreDashboard';
    import { updateThresholdByMonth, updateTotalTrsByMonth, updateBalanceEcoByMonth, updateBiggestTrsByMonth } from '@/storesPinia/useUpdateStoreByBackend';
    import ContainerSpinner from '@/components/container/ContainerSpinner.vue';
    import useSpinnerLoadData from '@/composables/useSpinnerLoadData';
    import { formatFloatAsString } from '@/composables/useMath';
    import TransitionOpacity from '@/components/transition/TransitionOpacity.vue';
    
    // store Pinia
    const threshold = storeThreshold();
    const dateSelected = storeDateSelected();
    const statisticDetails = storeStatisticDetails();
    
    // props, variables, ...
    const firstNameUser = ref('');
    const { isLoadedData, timerLoadPageSpinner } = useSpinnerLoadData();
    const topTransactions = reactive({
        typeTrsPurchases: false,
        typeTrsRecurrings: true
    });

    // life cycle / functions
    onMounted(async () => {
        isLoadedData.value = false;
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
        const timeMountedComponent = Date.now();
        await Promise.all([
            updateThresholdByMonth(newMonth, newYear),
            updateTotalTrsByMonth(newMonth, newYear),
            updateBiggestTrsByMonth(newMonth, newYear, 'purchase'),
            updateBiggestTrsByMonth(newMonth, newYear, 'recurring'),
            updateBalanceEcoByMonth(newMonth, newYear),
        ]);
        timerLoadPageSpinner(timeMountedComponent);
    }, {  immediate:true, deep:true });

    // computed
    const textBalanceEconomy = computed(() => {
        if(Math.abs(threshold.amount) < 0.001) return 'Aucun seuil défini';
        if(statisticDetails.economyBalance === null) return 'Aucune donnée';
        if(Math.abs(statisticDetails.economyBalance) < 0.001)  return 'Limite atteinte';
        if((statisticDetails.economyBalance > 0)) return '+'+ formatFloatAsString(statisticDetails.economyBalance) + ' €';
        return formatFloatAsString(statisticDetails.economyBalance) +' €';
    });
    const textTotalTransaction = computed (() => {
        return formatFloatAsString(statisticDetails.totalTransactions) + ' €';
    });
    const textThreshold = computed(() => {
        return formatFloatAsString(threshold.amount) +' €';
    });
    const colorTextBalanceEconomy = computed(() => {
        const balanceEconomyValue = statisticDetails.economyBalance;
        if(statisticDetails.economyBalance === threshold.amount) return 'text-white';
        if(Math.abs(threshold.amount) < 0.001) return 'text-white';
        if(balanceEconomyValue > 0) return 'text-custom-green';
        if(balanceEconomyValue < 0) return 'text-custom-red';
        return 'text-white';
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