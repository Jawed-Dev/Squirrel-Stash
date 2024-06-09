<template>

    <aside class="font-main-font flex flex-col bg-main-bg w-[100%]">

        <HeaderComponent/>

        <div class="ml-[calc(20px+70px+20px)] mr-custom-margin-main flex flex-col mt-[20px]">
            <h1 class="text-[25px] font-extralight text-white">Économie du mois</h1>
            <p class="font-normal py-3 mr-[190px] text-white">Bonjour Jawed, voici votre résumé du mois.</p> 

            <ContainerStatMonth :isIconActive="true" :svg="svgConfig('target', 'bg-gradient-blue')" :colorValue="'text-white'" :amountValue="'2000€'" :nameEconomy="'Seuil Mensuelle'" :width="'w-[520px]'"/>

            <section class="flex justify-between pt-[20px]">
                <div class="flex gap-[20px] ">
                    <SelectInput :listSelect="listMonth" />
                    <SelectInput :listSelect="listYear" />
                </div>
                <div class="flex justify-end">
                    <AddPurchase width="w-[32%]"/>
                </div>
            </section>

            <section class="w-[100%] mt-custom-margin-main rounded-[3px] overflow-hidden shadow-black shadow-custom-main"> 
                <ContainerPurchasesDays />
            </section>

            <section class="flex gap-[20px] justify-around ">
                <ContainerStatMonth :svg="svgConfig('balance', 'bg-gradient-green')" :colorValue="'text-custom-green'" :amountValue="'+500€'" :nameEconomy="'Balance d\'économie'" :width="'w-[25%]'" />
                <ContainerStatMonth :svg="svgConfig('calculator', 'bg-gradient-orange')" :colorValue="'text-custom-orange'" :amountValue="'1500€'" :nameEconomy="'Total d\'achats'" :width="'w-[25%]'" />
                <ContainerStatMonth :svg="svgConfig('restaurant', 'bg-gradient-blue')" :colorValue="'text-white'" :amountValue="'Restaurant'" :nameEconomy="'Plus gros achat / Catégorie'" :width="'w-[25%]'" />
                <ContainerStatMonth :svg="svgConfig('house', 'bg-gradient-vanusa')" :colorValue="'text-white'"  :amountValue="'Loyer'" :nameEconomy="'Plus gros prélèvement / Catégorie'" :width="'w-[25%]'" />
            </section>

            <section class="flex justify-between ">
                <ContainerListPurchases class="w-[calc(50%-10px)]" :title="'Historique des achats'" :purchaseType="'standard'"  :svg="svgConfig('restaurant', 'bg-gradient-blue', '6%')" />
                <ContainerListPurchases class=" w-[calc(50%-10px)]" :title="'Historique des prélèvements'" :purchaseType="'recuring'" :svg="svgConfig('balance', 'bg-gradient-vanusa', '6%')" />
            </section>
    
        </div>
    </aside>
</template>


<script setup>

    // import
    import HeaderComponent from '../components/header/Header.vue';
    import ContainerStatMonth from '../components/container/statistic/ContainerStatMonth.vue';
    import SelectInput from '@/components/select/SelectInput.vue';
    import ContainerPurchasesDays from '../components/container/statistic/ContainerPurchasesDays.vue';
    import ContainerListPurchases from '../components/container/statistic/ContainerListPurchases.vue';
    import AddPurchase from '@/components/overlay/AddPurchase.vue';

    // variables, props, ...

    const listMonth = [
        'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
    ];
    const listYear = [
        '2023',
        '2024',
        '2025'
    ];

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