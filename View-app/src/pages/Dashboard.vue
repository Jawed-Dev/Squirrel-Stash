<template>

    <div class="font-main-font flex flex-col bg-main-bg w-[100%]">

        <HeaderComponent/>

        <main class="ml-[calc(20px+70px+20px)] mr-custom-margin-main flex flex-col mt-[20px]">
            <h1 class="text-[25px] font-extralight text-white">Économie du mois</h1>

            <p class="font-normal py-3 mr-[190px] text-white">Bonjour Jawed, voici votre résumé du mois.</p> 

            <ContainerStatMonth :showIconConfig="true" :svg="svgConfig('target', 'bg-gradient-blue')" :colorValue="'text-white'" :strValue="'2000€'" :nameEconomy="'Seuil Mensuelle'" :width="'w-[520px]'"/>

            <section class="flex gap-[20px] pt-[20px]">
                <SelectInput :listSelect="listMonth" />
                <SelectInput :listSelect="listYear" />
            </section>

            <AddPurchase />

            <section class="w-[100%] mt-custom-margin-main rounded-[3px] overflow-hidden shadow-black shadow-custom-main"> 
                <ContainerTopMonthPc />
            </section>

            <section class="flex gap-[20px] justify-around ">
                <ContainerStatMonth :svg="svgConfig('balance', 'bg-gradient-green')" :colorValue="'text-custom-green'" :strValue="'+500€'" :nameEconomy="'Balance d\'économie'" :width="'w-[25%]'" />
                <ContainerStatMonth :svg="svgConfig('purchaseFood', 'bg-gradient-orange')" :colorValue="'text-custom-orange'" :strValue="'1500€'" :nameEconomy="'Total d\'achats'" :width="'w-[25%]'" />
                <ContainerStatMonth :svg="svgConfig('restaurant', 'bg-gradient-blue')" :colorValue="'text-white'" :strValue="'Restaurant'" :nameEconomy="'Plus gros achat / Catégorie'" :width="'w-[25%]'" />
                <ContainerStatMonth :svg="svgConfig('restaurant', 'bg-gradient-vanusa')" :colorValue="'text-white'"  :strValue="'Loyer'" :nameEconomy="'Plus grosse réccurence / Catégorie'" :width="'w-[25%]'" />
            </section>

            <section class="flex justify-between ">
                <ContainerListPurchases class="w-[calc(50%-10px)]" :title="'Historique de transactions'" :purchaseType="'standard'"  :svg="svgConfig('restaurant', 'bg-gradient-blue', '50px')" />
                <ContainerListPurchases class=" w-[calc(50%-10px)]" :title="'Paiements récurents'" :purchaseType="'recuring'" :svg="svgConfig('balance', 'bg-gradient-vanusa', '50px')" />
            </section>
    
        </main>
    </div>

    

</template>


<script setup>

    // import
    import HeaderComponent from '../components/header/Header.vue';
    import ContainerStatMonth from '@/components/container/ContainerStatMonth.vue';
    import SelectInput from '@/components/select/SelectInput.vue';
    import ContainerTopMonthPc from '../components/container/ContainerTopMonthPc.vue';
    import ContainerListPurchases from '@/components/container/ContainerListPurchases.vue';
    import AddPurchase from '@/components/overlay/AddPurchase.vue';
    import { ref } from 'vue';

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
    function svgConfig(nameSvg, colorIcon, width = '60px') {
        return {
            name: nameSvg,
            color: colorIcon,
            width: width,
            height: width,
        }
    }

</script>