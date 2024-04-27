<template>

    <div class="flexh-[100vh] font-main-font bg-slate-200">
        <header-component/>
        <main class="ml-[170px] flex flex-col w-[100%]">
            <section class="rounded-lg pl-5 bg-white shadow-xl w-[100%] ">
                <h1 class="py-3 text-[25px] font-medium border-b">Economie du mois</h1>

                <p class="font-medium py-3 mr-[190px]">Bonjour Jawed, voici votre résumé du mois.</p>

                <div class="flex items-center gap-[50px] ">
                    <select class="w-[250px]" name="month" id="month-select">
                        <option value="">Année</option>
                        <option value="1">Janvier</option>
                        <option value="2">Février</option>
                        <option value="3">Mars</option>
                        <option value="4">Avril</option>
                        <option value="5">Mai</option>
                        <option value="6">Juin</option>
                        <option value="7">Juillet</option>
                        <option value="8">Août</option>
                        <option value="9">Septembre</option>
                        <option value="10">Octobre</option>
                        <option value="11">Novembre</option>
                        <option value="12">Décembre</option>
                    </select>

                    <select class="w-[250px]" name="year" id="year-select">
                        <option value="">Année</option>

                        <!-- Générez les années dynamiquement avec JavaScript si nécessaire -->
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <!-- etc. -->
                    </select>

                    <div class="flex flex-col text-[17px] w-[250px] border-[FFFFF] border rounded-md shadow-md my-5">
                        <p class="pt-3 pl-[13px] text-gray-600 font-medium">Goal d'économie</p>
                        <p class="pl-[13px] text-blue-700 font-semibold  text-[25px]">2000€</p>

                        <p class="pl-[13px] text-[15px] mt-5 py-1 border-t font-medium text-gray-600">Dernier Mois: <span class="font-medium text-blue-700 text-[18px]">2200€</span></p>
                    </div>

                    
                </div>
                

                <div class="flex gap-[50px]">
        
                    <div class="flex flex-col text-[17px] w-[250px] border-[FFFFF] border rounded-md shadow-md my-5">
                        <p class="pt-3 pl-[13px] text-gray-600 font-medium">Total achat</p>
                        <p class="pl-[13px] text-blue-700 font-semibold  text-[25px] ">2000€</p>

                        <p class="pl-[13px] text-[15px] mt-5 py-1 border-t font-medium text-gray-600">Dernier Mois: <span class="font-medium text-blue-700 text-[18px]">2200€</span></p>
                    </div>

                    <div class="flex flex-col text-[17px] w-[250px] border-[FFFFF] border rounded-md shadow-md my-5">
                        <p class="pt-3 pl-[13px] text-gray-600 font-medium">Statut d'économie</p>
                        <p class="pl-[13px]  font-semibold text-blue-700  text-[25px] ">2000€</p>

                        <p class="pl-[13px] text-[15px] mt-5 py-1 border-t font-medium text-gray-600">Dernier Mois: <span class="font-medium text-blue-700 text-[18px]">2200€</span></p>
                    </div>

                    <div class="flex flex-col text-[17px] w-[250px] border-[FFFFF] border rounded-md shadow-md my-5">
                        <p class="pt-3 pl-[13px] text-gray-600 font-medium">Top achat mensuel</p>
                        <p class="pl-[13px]  font-semibold text-blue-700  text-[25px] ">Nourriture</p>

                        <p class="pl-[13px] text-[15px] mt-5 py-1 border-t font-medium text-gray-600">Dernier Mois: <span class="font-medium text-blue-700 text-[18px]">Nourriture</span></p>
                    </div>

                    <div class="flex flex-col text-[17px] w-[250px] border-[FFFFF] border rounded-md shadow-md my-5">
                        <p class="pt-3 pl-[13px] text-gray-600 font-medium">Top paiement réccurent</p>
                        <p class="pl-[13px] text-blue-700 font-semibold  text-[25px]">2000€</p>

                        <p class="pl-[13px] text-[15px] mt-5 py-1 border-t font-medium text-gray-600">Dernier Mois: <span class="font-medium text-blue-700 text-[18px]">2200€</span></p>
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



</script>