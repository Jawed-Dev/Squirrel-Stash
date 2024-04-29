<template>

    <div class="font-main-font flex flex-col bg-[#040D12] w-[100%]">
        <header-component/>

        <main class="ml-[calc(20px+60px+20px)] mr-custom-margin-main flex flex-col">


            <section class="text-white pl-5 mt-top-Header 
                bg-gradient
                border border-custom-gray-dark rounded-md 
                shadow-custom-gray-dark shadow-custom-main">
                <h1 class="py-3 text-[25px] font-light border-b border-[#38393b]">Economie du mois</h1>

                <p class="font-light py-3 mr-[190px]">Bonjour Jawed, voici votre résumé du mois.</p>

                
                <div class="flex items-center gap-[20px]">
                    <select-component :listSelect="listMonth" />

                    <select-component :listSelect="listYear" />
                </div>
                
                <div class="flex flex-col">
                    <div class="flex gap-[20px] flex-wrap">
                        <div-text-stat :nameEconomy="'Objectif du mois'" />

                        <div-text-stat :nameEconomy="'Total achat'" />
    
                        <div-text-stat :nameEconomy="'Balance d\'économie'" />

                        <div-text-stat :nameEconomy="'Top achat mensuel'" />
    
                        <div-text-stat :nameEconomy="'Top paiement réccurent'" />
    
                    </div>
                </div>
            </section>


            <section-purchase />

            <section class="flex justify-between">

                <div class="pl-2 mt-custom-margin-main w-[49%] text-white 
                    border border-custom-gray-dark bg-gradient rounded-md my-5 
                    shadow-custom-gray-dark shadow-custom-main">     

                    <div class="flex items-center justify-between pr-10">
                        <h2 class="py-3 text-[20px] font-light pr-8">Historique de transaction</h2>
                        <p>Voir plus ></p>
                    </div>

                    <div class="flex border-[#38393b] border-b pl-3 pt-5">
                        <p class="w-[150px]">Nom</p>
                        <p class="w-[150px]">Prix</p>
                        <p class="w-[150px]">Date</p>
                        <p class="w-[150px]">Itération</p>
                    </div>
    
                    <div class="pl-3">
                        <purchase :infoPurchase="infoPurchase"/>
        
                        <purchase :infoPurchase="infoPurchase"/>
    
                        <purchase :infoPurchase="infoPurchase"/>
    
                        <purchase :infoPurchase="infoPurchase"/>
    
                        <purchase :infoPurchase="infoPurchase"/>
                    </div>
    
                </div>


                <div class="pl-2 mt-custom-margin-main w-[49%] text-white 
                    border border-custom-gray-dark bg-gradient rounded-md my-5 
                    shadow-custom-gray-dark shadow-custom-main">    

                    <div class="flex items-center justify-between pr-10">
                        <h2 class="py-3 text-[20px] font-light pr-8">Paiments réccurents</h2>
                        <p>Voir plus ></p>
                    </div>


                    <div class="flex border-[#38393b] border-b pl-3 pt-5">
                        <p class="w-[150px]">Nom</p>
                        <p class="w-[150px]">Prix</p>
                        <p class="w-[150px]">Date</p>
                    </div>

                    <div class="pl-3">
                        <reccuring-pay :infoReccuringPay="infoRecPay"/>

                        <reccuring-pay :infoReccuringPay="infoRecPay"/>

                        <reccuring-pay :infoReccuringPay="infoRecPay"/>
                    </div>
                </div>

            </section>
            
        </main>
    </div>

    

</template>


<script setup>

    import headerComponent from '../components/header/Header.vue';
    import divTextStat from '../components/statistic/TextStat.vue';
    import selectComponent from '../components/select/Select.vue';
    import Purchase from '../components/statistic/Purchase.vue';
    import ReccuringPay from '../components/statistic/RecurringPay.vue';
    import SectionPurchase from '../components/home/Section-Purchase.vue';


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

    const infoPurchase = {
        name: 'Spotify',
        price: 100,
        date: '26 Mars 2023 15:50',
        iteration: 10
    }

    const infoRecPay = {
        name: 'Electricité',
        price: 100,
        date: '26 Mars 2023 15:50',
    }

    

</script>