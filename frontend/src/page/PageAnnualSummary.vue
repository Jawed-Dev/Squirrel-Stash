<template>
    <div class="font-main flex flex-col min-h-screen bg-main-bg w-full pb-[50px] md:pb-0">
        <div class="mx-1 md:ml-[calc(20px+65px+20px)] xl:ml-[calc(30px+75px+30px)] md:mr-[20px] xl:mr-[30px] flex flex-col my-5">

            <h1 class="text-2xl font-light text-white">Récapitulatif de l'année</h1>

            <div class="mt-5 sm:w-full lg:w-1/6">
                <SelectYear 
                    class="min-h-[42px] w-full sm:min-w-[250px]" 
                    v-model="yearSelected" 
                    :listSelect="getAvailableYear()"
                />
            </div>

<section class="w-full flex flex-col xl:flex-row gap-5 mt-custom-margin-main rounded-[3px]"> 
    <ContainerGraphYear 
        title1="Achats par mois" title2="Prélèvements par mois"
        :dataTransaction="yearListTrsByMonth.data" 
        :typeGraph="useGraphLine" 
        v-model="typeTransaction.graphByMonth"
        :class="'w-full xl:w-1/2  shadow-main overflow-hidden'" 
    />
    <ContainerGraphYear 
        title1="Achats par catégorie" title2="Prélèvements par catégorie"
        :dataTransaction="yearListTrsByCategories.data" 
        :typeGraph="useGraphBar" 
        v-model="typeTransaction.graphByCategories"
        :class="'w-full xl:w-1/2  shadow-main overflow-hidden '" 
    />
</section>
 
            <section class="flex flex-col sm:flex-row sm:gap-5 justify-around">
                <div class="flex flex-col w-full justify-around min-[1340px]:flex-row min-[1340px]:gap-5">
                    <ContainerStatMonth 
                        nameIcon="calculator" bgIcon="bg-gradient-orange" :colorValue="'text-custom-orange'" 
                        :amountValue="textStatsByYear.totalTransactions" nameStat="Total des transactions" 
                        :width="'min-[1340px]:w-1/2 w-full'" 
                    />

                    <ContainerStatMonth 
                        nameIcon="balance" bgIcon="bg-gradient-green" :colorValue="'text-white'" 
                        :amountValue="textStatsByYear.biggestMonth" nameStat="Mois le plus coûteux" 
                        :width="'min-[1340px]:w-1/2 w-full'" 
                    />
                </div>

                <div class="flex flex-col w-full justify-around min-[1340px]:flex-row min-[1340px]:gap-5 ">
                    <ContainerStatMonth 
                        :nameIcon="iconNamePurchases" bgIcon="bg-gradient-blue" :colorValue="'text-white'" 
                        :amountValue="nameBiggestPurchase" nameStat="Plus gros achat / catégorie" 
                        :width="'min-[1340px]:w-1/2 w-full'" 
                    />

                    <ContainerStatMonth 
                        :nameIcon="iconNameRecurrings" bgIcon="bg-gradient-vanusa" :colorValue="'text-white'" 
                        :amountValue="nameBiggestRecurring" nameStat="Plus gros prélèvement / catégorie" 
                        :width="'min-[1340px]:w-1/2 w-full'" 
                    />
                </div>
            </section>

            <section class="w-full flex flex-col xl:flex-row gap-5 mt-custom-margin-main rounded-[3px]"> 
                <ContainerGraphYear 
                    title1="Plus gros achats"
                    :dataTransaction="topYearCategories.listPurchases" 
                    :hideToggleButton="true" 
                    v-model="typeTransaction.donutTopPurchases" 
                    :typeGraph="useGraphDonut" 
                    :class="'w-full xl:w-1/2 shadow-main overflow-hidden'" 
                />
        
                <ContainerGraphYear 
                    title2="Plus gros prélèvements"
                    :dataTransaction="topYearCategories.listRecurrings" 
                    :hideToggleButton="true" 
                    v-model="typeTransaction.donutTopRecurring" 
                    :typeGraph="useGraphDonut" 
                    :class="'w-full xl:w-1/2 shadow-main overflow-hidden'" 
                />
            </section>

        </div>
    </div>
</template>

<script setup>
    import { ref, reactive, watch, computed } from 'vue'; 
    import SelectYear from '@/component/select/SelectYear.vue';
    import { getAvailableYear, getCurrentYear } from '@/composable/useGetDate';
    import ContainerStatMonth from '@/component/container/ContainerStatMonth.vue';
    
    import ContainerGraphYear from '@/component/container/ContainerGraphYear.vue';
    import { 
            storeYearListTrsByMonth, storeTextStatsByYear, 
            storeYearListTrsByCategories, storeTopYearCategories } from '@/storePinia/useStoreDashboard';
    import { 
            updateStoreYearListTrsByMonth, updateStoreTotalTrsByYear, updateBiggestTrsByYear,
            updateStoreBiggestMonthByYear, updateStoreYearListTrsByCategories, 
            updateStoreTopYearCategories
        } from '@/storePinia/useUpdateStoreByBackend';
        
    import useGraphLine from '@/composable/useGraphLine.vue';
    import useGraphBar from '@/composable/useGraphBar.vue';
    import useGraphDonut from '@/composable/useGraphDonut.vue';

    
    // store Pinia
    const yearListTrsByMonth = storeYearListTrsByMonth();
    const yearListTrsByCategories = storeYearListTrsByCategories();
    const textStatsByYear = storeTextStatsByYear();
    const topYearCategories = storeTopYearCategories();

    // props, variables
    const currentYear = getCurrentYear();
    const yearSelected = ref(currentYear);
    const typeTransaction = reactive({
        graphByMonth: false,
        graphByCategories: false,
        donutTopPurchases: false,
        donutTopRecurring: true,
    });

    // fonctions, life cycle
    const iconNamePurchases = computed(() => {
        const nameIcon = textStatsByYear?.biggestPurchase;
        return (nameIcon) ? nameIcon : 'Invisible';
    });
    const iconNameRecurrings = computed(() => {
        const nameIcon = textStatsByYear?.biggestRecurring;
        return (nameIcon) ? nameIcon : 'Invisible';
    });
    const nameBiggestPurchase = computed(() => {
        const nameBiggestPurch = textStatsByYear?.biggestPurchase;
        return (nameBiggestPurch) ? nameBiggestPurch : 'Aucune donnée';
    });
    const nameBiggestRecurring = computed(() => {
        const nameBiggestPurch = textStatsByYear?.biggestRecurring;
        return (nameBiggestPurch) ? nameBiggestPurch : 'Aucune donnée';
    });


    watch(yearSelected, async (newYear) => {
        await updateStoreYearListTrsByMonth(newYear, getNameTypeTransaction(typeTransaction.graphByMonth));
        await updateStoreTotalTrsByYear(newYear);
        await updateBiggestTrsByYear(newYear,'purchase');
        await updateBiggestTrsByYear(newYear,'recurring');
        await updateStoreBiggestMonthByYear(newYear);
        await updateStoreYearListTrsByCategories(newYear, getNameTypeTransaction(typeTransaction.graphByCategories));
        await updateStoreTopYearCategories(newYear, 'purchase');
        await updateStoreTopYearCategories(newYear, 'recurring');
    }, { immediate: true, deep: true });

    watch( () => [typeTransaction.graphByMonth], async () => {
        await updateStoreYearListTrsByMonth(yearSelected.value, getNameTypeTransaction(typeTransaction.graphByMonth));
    }, {  immediate:true, deep:true });

    watch( () => [typeTransaction.graphByCategories], async () => {
        await updateStoreYearListTrsByCategories(yearSelected.value, getNameTypeTransaction(typeTransaction.graphByCategories));
    }, {  immediate:true, deep:true });

    function getNameTypeTransaction(typeTransaction)  {
        return (!typeTransaction) ? 'purchase' : 'recurring';
    }

</script>