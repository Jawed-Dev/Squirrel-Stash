<template>

    <div class="font-main-font flex flex-col bg-main-bg w-[100%]">

        <header-component/>

        <main class="ml-[calc(20px+70px+20px)] mr-custom-margin-main flex flex-col text-white">


            <h1 class="py-3 text-[25px] font-extralight ">Économie du mois</h1>

            <p class="font-normal py-3 mr-[190px]">Bonjour Jawed, voici votre résumé du mois.</p> 

            <div class="flex gap-[20px]">
                <select-component :listSelect="listMonth" />
                <select-component :listSelect="listYear" />
            </div>

            <section-purchase />

            <div class="flex gap-[20px] justify-aroundflex-wrap">
                <div-text-stat :strValue="'350€'" :nameEconomy="'Total achat'" />

                <div-text-stat :strValue="'Restaurant'" :nameEconomy="'Plus grand achat'" />

                <div-text-stat :strValue="'Loyer'" :nameEconomy="'Plus grand paiement réccurent'" />

                <div-text-stat :strValue="'+500€'" :nameEconomy="'Balance économie'" />

            </div>


            <section class="flex justify-between">

                <container-purchase />
                <container-reccuring-pay />

            </section>
            
        </main>
    </div>

    

</template>


<script setup>

    import headerComponent from '../components/header/Header.vue';
    import divTextStat from '../components/statistic/TextStat.vue';
    
    import selectComponent from '../components/select/Select.vue';
    import SectionPurchase from '../components/statistic/ContainerGraphPurchase.vue';
    import ContainerPurchase from '@/components/statistic/ContainerPurchase.vue';
    import ContainerReccuringPay from '@/components/statistic/ContainerReccuringPay.vue'

    import { ref } from 'vue';


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

    

</script>