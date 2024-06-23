<template>

    <aside class="font-main-font flex flex-col bg-main-bg w-[100%]">

        <HeaderComponent/>

        <div class="ml-[calc(20px+70px+20px)] mr-custom-margin-main flex flex-col mt-[20px]">
            <h1 class="text-[25px] font-extralight text-white">Économie du mois</h1>
            <p class="font-normal py-3 mr-[190px] text-white">Bonjour Jawed, voici votre résumé du mois.</p> 

            <ContainerStatMonth :isIconActive="true" :svg="svgConfig('target', 'bg-gradient-blue')" :colorValue="'text-white'" :amountValue="currentThreshold.amount +' €'" :nameStat="'Seuil mensuel'" :width="'w-[520px]'"/>

            <section class="flex justify-between pt-[20px]">
                <div class="flex gap-[20px] ">
                    <SelectMonth v-model="dateSelected.month" :listSelect="monthNames" />
                    <SelectYear v-model="dateSelected.year" :listSelect="getAvailableYearNames()" />
                </div>
                <div class="flex justify-end">
                    <AddPurchase width="w-[30vw]"/>
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
                
                <ContainerStatMonth :svg="svgConfig('restaurant', 'bg-gradient-blue')" :colorValue="'text-white'" 
                :amountValue="statisticDetails.biggestPurchase" :nameStat="'Plus gros achat / Catégorie'" :width="'w-[25%]'" />
                <ContainerStatMonth :svg="svgConfig('house', 'bg-gradient-vanusa')" :colorValue="'text-white'"  
                :amountValue="statisticDetails.biggestRecurring" :nameStat="'Plus gros prélèvement / Catégorie'" :width="'w-[25%]'" />
            </section>

            <section class="flex justify-between ">
                <ContainerListPurchases class="w-[calc(50%-10px)]" :title="'Historique des achats'" :purchaseType="'purchase'"  :svg="svgConfig('restaurant', 'bg-gradient-blue', '6%')" />
                <ContainerListPurchases class=" w-[calc(50%-10px)]" :title="'Historique des prélèvements'" :purchaseType="'recurring'" :svg="svgConfig('balance', 'bg-gradient-vanusa', '6%')" />
            </section>
    
        </div>
    </aside>
</template>


<script setup>

    // import
    import { ref, onMounted, watch, computed } from 'vue'; 
    import HeaderComponent from '@/components/header/Header.vue';
    import ContainerStatMonth from '@/components/container/statistic/ContainerStatMonth.vue';
    import SelectYear from '@/components/select/SelectYear.vue';
    import SelectMonth from '@/components/select/SelectMonth.vue';
    import ContainerTransactionsMonth from '@/components/container/statistic/ContainerTransactionsMonth.vue';
    import ContainerListPurchases from '@/components/container/statistic/ContainerListPurchases.vue';
    import AddPurchase from '@/components/overlay/AddPurchase.vue';
    import { monthNames, getAvailableYearNames } from '@/composable/useGetDate';
    import { getBiggestTrsByMonth, getThresholdByMonth, getTotalTrsByMonth } from '@/composable/useBackendStatisticFunction';
    import { storeThreshold, storeDateSelected, storeStatisticDetails } from '@/store/useStoreDashboard';

    // stores
    const currentThreshold = storeThreshold();
    const dateSelected = storeDateSelected();
    const statisticDetails = storeStatisticDetails();

    // variables, props, ...
    
    // life cycle / functions
    watch( () => [dateSelected.year, dateSelected.month], async ([newYear, newMonth]) => {

        const thresholdFetched = await getThresholdByMonth(newMonth, newYear);
        const thresholdAmount = thresholdFetched?.data?.threshold_amount;
        console.log(thresholdAmount);
        currentThreshold.amount  = (thresholdAmount) ? thresholdAmount : 0;

        const totalTransactionsFetched = await getTotalTrsByMonth(newMonth, newYear);
        const totalTransactions = totalTransactionsFetched?.data?.total_transactions;
        console.log(totalTransactions);
        statisticDetails.totalTransactions = (totalTransactions) ? totalTransactions : 0;

        const economyBalanceValue = currentThreshold.amount - statisticDetails.totalTransactions;
        statisticDetails.economicBalance = (economyBalanceValue) ? economyBalanceValue : 0;

        const biggestPurchaseFetched = await getBiggestTrsByMonth(newMonth, newYear, 'purchase');
        const biggestPurchase = biggestPurchaseFetched?.data?.transaction_name;
        statisticDetails.biggestPurchase = (biggestPurchase) ? biggestPurchase : 'Aucune donnée';
      
        const biggestRecurringFetched = await getBiggestTrsByMonth(newMonth, newYear, 'recurring');
        const biggestRecurring = biggestRecurringFetched?.data?.transaction_name;
        statisticDetails.biggestRecurring = (biggestRecurring) ? biggestRecurring : 'Aucune donnée';

    }, {  immediate:true, deep:true });

    // computed
    const filterTextBalanceEconomy = computed(() => {
        if((statisticDetails.economicBalance === 0)) return '0 €';
        else if((statisticDetails.economicBalance > 0)) return '+ '+ statisticDetails.economicBalance + ' €';
        else return '- '+statisticDetails.economicBalance +' €';
    });


    // functions
    function svgConfig(nameSvg, color, width = '60px') {
        return {
            name: nameSvg,
            color: color,
            width: width,
            height: width,
            fill: 'white'
        }
    }

</script>