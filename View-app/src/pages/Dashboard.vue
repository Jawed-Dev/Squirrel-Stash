<template>

    <div class="font-main-font flex flex-col bg-main-bg w-[100%]">

        <header-component/>

        <main class="ml-[calc(20px+70px+20px)] mr-custom-margin-main flex flex-col mt-[20px]">
            <h1 class="text-[25px] font-extralight text-white">Économie du mois</h1>

            <p class="font-normal py-3 mr-[190px] text-white">Bonjour Jawed, voici votre résumé du mois.</p> 

            <div-text-stat :svg="svgConfig('target', 'bg-gradient-blue')" :colorValue="'text-custom-red'" :strValue="'350€'" :nameEconomy="'Objectif du mois'" :width="'w-[520px]'"/>

            <section class="flex gap-[20px] pt-[20px]">
                <select-component :listSelect="listMonth" />
                <select-component :listSelect="listYear" />
            </section>

         
            <AddPurchase />

            
            <section-purchase />

            <section class="flex gap-[20px] justify-around ">
                <div-text-stat :svg="svgConfig('balance', 'bg-gradient-green')" :colorValue="'text-custom-purple'" :strValue="'+500€'" :nameEconomy="'Balance économie'" :width="'w-[25%]'" />
                <div-text-stat :svg="svgConfig('purchaseFoods', 'bg-gradient-blue')" :colorValue="'text-custom-blue'" :strValue="'350€'" :nameEconomy="'Total achat'" :width="'w-[25%]'" />
                <div-text-stat :svg="svgConfig('restaurant', 'bg-gradient-orange')" :colorValue="'text-custom-orange'" :strValue="'Restaurant'" :nameEconomy="'Plus gros achat / Type'" :width="'w-[25%]'" />
                <div-text-stat :svg="svgConfig('restaurant', 'bg-gradient-green')" :colorValue="'text-custom-green'"  :strValue="'Loyer'" :nameEconomy="'Plus gros débitement / Type'" :width="'w-[25%]'" />
            </section>

            <section class="flex justify-between">
                <container-purchase :title="'Historique de transactions'" :purchaseType="'standard'"  :svg="svgConfig('restaurant', 'bg-gradient-red', '50px')" />
                <container-purchase :title="'Paiements récurents'" :purchaseType="'recuring'" :svg="svgConfig('balance', 'bg-gradient-orange', '50px')" />
            </section>
    
        </main>
    </div>

    

</template>


<script setup>

    // import
    import headerComponent from '../components/header/Header.vue';
    import divTextStat from '@/components/statistic/TextStat.vue';
    import selectComponent from '@/components/select/Select.vue';
    import SectionPurchase from '../components/statistic/ContainerGraphPurchase.vue';
    import ContainerPurchase from '@/components/statistic/ContainerPurchase.vue';
    import AddPurchase from '@/components/overlay/AddPurchase.vue';
    import { ref } from 'vue';
    import TransitionOpacity from '@/components/transition/TransitionOpacity.vue';

    // variables, props, ...

    const ismenuAddActive = ref(false);

    const listMonth = [
        'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
    ];
    const listYear = [
        '2023',
        '2024',
        '2025'
    ];
    const infoRecPay = {
        name: 'Electricité',
        price: 100,
        date: '26 Mars 2023 15:50',
    }

    const svgConfig2 =  {
        width: '70px',
        height: '70px',
        fill: 'white'
    }

    const svgConfig3 =  {
        width: '100px',
        height: '100px',
        fill: 'white'
    }

    // functions
    function svgConfig(nameSvg, colorIcon, width = '60px') {
        return {
            name: nameSvg,
            color: colorIcon,
            width: width,
            height: width,
        }
    }


    function togMenuAddPurchase() {
        ismenuAddActive.value = !ismenuAddActive.value;
    }
</script>