<template>

    <div class="font-main-font flex flex-col bg-black w-[100%]">
        <header-component/>
        <main class="ml-[calc(170px+10px)] mr-[10px] flex flex-col">
            <section class="text-white rounded-lg pl-5 bg-black shadow-xl ">
                <h1 class="py-3 text-[25px] font-light border-b">Economie du mois</h1>

                <p class="font-light py-3 mr-[190px]">Bonjour Jawed, voici votre résumé du mois.</p>

                <div class="flex items-center gap-[50px]">
                    <select-component :listSelect="listMonth" />

                    <select-component :listSelect="listYear" />
                
                    <div-text-stat :nameEconomy="'Goal du mois'" />
                </div>
                
                <div class="flex gap-[50px]">
                    <div class="flex gap-[50px]">
                        <div-text-stat :nameEconomy="'Total achat'" />
    
                        <div-text-stat :nameEconomy="'Statut d\'économie'" />
                    </div>
    
                    <div class="flex gap-[50px]">
                        <div-text-stat :nameEconomy="'Top achat mensuel'" />
    
                        <div-text-stat :nameEconomy="'Top paiement réccurent'" />
    
                    </div>
                </div>

                    
                
            </section>


            <section class="w-[100%] rounded-lg pl-5 mt-[28px] bg-white shadow-md border-2 border-[FFFFF]">
                <h2 class="py-3 text-[25px] font-medium">Achats du mois</h2>
                

                <div class="flex gap-[50px] py-3 border-b-2 ">
                    <p class="border-b-2 border-main-blue text-gray-500 cursor-pointer">Achats</p>
                    <p class="border-b-2 border-main-blue text-gray-500 cursor-pointer">Paiement réccurent</p>
                </div>


                <div class="h-[500px]">
                        
                </div>

            </section>

            <section class="flex">

                <div class=" rounded-lg pl-5 mt-[28px] bg-white shadow-md border-2 border-[FFFFF] w-[50%]">                
                    <div class="flex items-center justify-between pr-5">
                        <h2 class="py-3 text-[25px] font-medium">Historique de transaction</h2>
                        <p>Voir plus ></p>
                    </div>
    
    
                    <div class="pl-[30px]">
                        <div class="flex  border-b">
                            <p class="w-[150px]">Nom</p>
                            <p class="w-[150px]">Prix</p>
                            <p class="w-[200px]">Date</p>
                            <p class="w-[150px]">Itération / mois</p>
                        </div>
        
                        <div class="flex border-b">
                            <p class="w-[150px] py-5">Spotify</p>
                            <p class="w-[150px] py-5">12€</p>
                            <p class="w-[200px] py-5">26 Mars 2023 15:50</p>
                            <p class="w-[150px] py-5">3x</p>
                        </div>
    
                        <div class="flex border-b">
                            <p class="w-[150px] py-5">Spotify</p>
                            <p class="w-[150px] py-5">12€</p>
                            <p class="w-[200px] py-5">26 Mars 2023 15:50</p>
                            <p class="w-[150px] py-5">3x</p>
                        </div>
    
                        <div class="flex border-b">
                            <p class="w-[150px] py-5">Spotify</p>
                            <p class="w-[150px] py-5">12€</p>
                            <p class="w-[200px] py-5">26 Mars 2023 15:50</p>
                            <p class="w-[150px] py-5">3x</p>
                        </div>
    
                        <div class="flex border-b">
                            <p class="w-[150px] py-5">Spotify</p>
                            <p class="w-[150px] py-5">1002€</p>
                            <p class="w-[200px] py-5">26 Mars 2023 15:50</p>
                            <p class="w-[150px] py-5">3x</p>
                        </div>
    
                    </div>
    
                </div>


                <div class=" rounded-lg pl-5 mt-[28px] bg-white shadow-md border-2 border-[FFFFF] w-[50%]">

                    <div class="flex items-center justify-between pr-5">
                        <h2 class="py-3 text-[25px] font-medium">Paiments réccurents</h2>
                        <p>Voir plus ></p>
                    </div>

                    <div class="pl-[30px]">
                        <div class="flex  border-b">
                            <p class="w-[150px]">Nom</p>
                            <p class="w-[150px]">Prix</p>
                            <p class="w-[200px]">Date</p>
                            <p class="w-[150px]">Itération / mois</p>
                        </div>

                        <div class="flex border-b">
                            <p class="w-[150px] py-5">Netflix</p>
                            <p class="w-[150px] py-5">20€</p>
                            <p class="w-[200px] py-5">26 Mars 2023 15:50</p>
                            <p class="w-[150px] py-5">3x</p>
                        </div>

                        <div class="flex border-b">
                            <p class="w-[150px] py-5">Electricité</p>
                            <p class="w-[150px] py-5">123€</p>
                            <p class="w-[200px] py-5">26 Mars 2023 15:50</p>
                            <p class="w-[150px] py-5">3x</p>
                        </div>
                    </div>
                </div>

            </section>
            
        </main>
    </div>

    

</template>


<script setup>
    import { Fragment } from 'vue';
    import headerComponent from '../components/header/Header.vue';
    import divTextStat from '../components/statistic/divTextStat.vue';
    import selectComponent from '../components/select/Select.vue';

    const listMonth = [
        'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
    ];

    const listYear = [
        '2023',
        '2024',
        '2025'
    ];


</script>