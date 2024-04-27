<template>

    <div class="font-main-font flex flex-col bg-black w-[100%]">
        <header-component/>

        <main class="ml-[calc(170px+10px)] mr-[10px] flex flex-col">


            <section class="text-white pl-5 bg-[linear-gradient(to_top_left,_#101010_80%,_#ffff_350%)]
                border border-[#38393b]  rounded-md my-5
                shadow-[#38393b] shadow-[0_0_10px_#38393b]">
                <h1 class="py-3 text-[25px] font-light border-b border-[#38393b]">Economie du mois</h1>

                <p class="font-light py-3 mr-[190px]">Bonjour Jawed, voici votre résumé du mois.</p>

                
                <div class="flex items-center gap-[50px]">
                    <select-component :listSelect="listMonth" />

                    <select-component :listSelect="listYear" />
                
                    
                </div>
                
                <div class="flex flex-col">
                    <div class="flex gap-[50px]">
                        <div-text-stat :nameEconomy="'Goal du mois'" />

                        <div-text-stat :nameEconomy="'Total achat'" />
                    </div>
    
                    <div class="flex gap-[50px]">
                        <div-text-stat :nameEconomy="'Balance d\'économie'" />

                        <div-text-stat :nameEconomy="'Top achat mensuel'" />
    
                        <div-text-stat :nameEconomy="'Top paiement réccurent'" />
    
                    </div>
                </div>
            </section>


            <section class="w-[100%] pl-5 mt-[28px] bg-[linear-gradient(to_top_left,_#101010_85%,_#ffff_350%)] text-white
                border border-[#38393b]  rounded-md my-5
                shadow-[#38393b] shadow-[0_0_10px_#38393b]">
                <h2 class="py-3 text-[25px] font-light">Achats du mois</h2>
                

                <div class="flex gap-[50px] py-3 border-b-2 border-[#38393b]">
                    <p class="border-b-2 border-main-blue cursor-pointer">Achats</p>
                    <p class="border-b-2 border-main-blue cursor-pointer">Paiement réccurent</p>
                </div>


                <div class="h-[500px]">
                        
                </div>

            </section>

            <section class="flex justify-between">

                <div class=" pl-5 mt-[28px] w-[45%] bg-black text-white 
                    border border-[#38393b] bg-[linear-gradient(to_top_left,_#101010_80%,_#ffff_350%)] rounded-md my-5 
                    shadow-[#38393b] shadow-[0_0_10px_#38393b]">                
                    <div class="flex items-center justify-between pr-5">
                        <h2 class="py-3 text-[25px] font-medium">Historique de transaction</h2>
                        <p>Voir plus ></p>
                    </div>

                    <div class="flex border-[#38393b] border-b pl-[30px]">
                        <p class="w-[150px]">Nom</p>
                        <p class="w-[150px]">Prix</p>
                        <p class="w-[200px]">Date</p>
                        <p class="w-[150px]">Itération / mois</p>
                    </div>
    
                    <div class="pl-[30px]">
                        <purchase :infoPurchase="infoPurchase"/>
        
                        <purchase :infoPurchase="infoPurchase"/>
    
                        <purchase :infoPurchase="infoPurchase"/>
    
                        <purchase :infoPurchase="infoPurchase"/>
    
                        <purchase :infoPurchase="infoPurchase"/>
                    </div>
    
                </div>


                <div class="pl-5 mt-[28px] w-[45%] text-white 
                    border border-[#38393b] bg-[linear-gradient(to_top_left,_#101010_80%,_#ffff_350%)] rounded-md my-5 
                    shadow-[#38393b] shadow-[0_0_10px_#38393b]">

                    <div class="flex items-center justify-between pr-5">
                        <h2 class="py-3 text-[25px] font-medium">Paiments réccurents</h2>
                        <p>Voir plus ></p>
                    </div>

                    <div class="pl-[30px]">
                        <div class="flex  
                            border-b border-[#38393b]">
                            <p class="w-[150px]">Nom</p>
                            <p class="w-[150px]">Prix</p>
                        </div>

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
    import ReccuringPay from '../components/statistic/RecurringPay.vue'

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
        name: 'Apanyan',
        price: 100,
    }

</script>