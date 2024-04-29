<template>
    <section 
        class="w-[100%] pl-5 mt-custom-margin-main bg-gradient text-white 
        border border-custom-gray-dark rounded-md
        shadow-[#38393b] shadow-custom-main">
        
        <h2 class="py-3 text-[25px] font-light">Achats du mois</h2>
        
        <div class="flex gap-[50px] py-3  border-b border-custom-gray-dark">
            <p class="px-3 py-1 rounded-md cursor-pointer" :class="[stateTabPurchase ? 'bg-custom-gray-2' : '']" @click="handleStateTab(true)" >Achats</p>
            <p class="px-3 py-1 rounded-md cursor-pointer" :class="[stateTabPurchase ? '' : 'bg-custom-gray-2']" @click="handleStateTab(false)" >Paiement réccurent</p>
        </div>


        <div class="text-[20px] py-[50px]" id="chart">

        </div>
      

    </section>
    
</template>

<script setup>
    

    import { ref , onMounted } from 'vue';
    import ApexCharts from 'apexcharts'

    function handleStateTab(state) {
        stateTabPurchase.value = state;
    }   
    const stateTabPurchase = ref(true);
        

    

    // responsive: [{
    //       breakpoint: 480,
    //       options: {
    //         chart: {
    //           width: 200
    //         },
    //         legend: {
    //           position: 'right',
    //         }
    //       }
    //     }]

    onMounted(() => {
        const options = {
            series: [44, 55, 41, 17, 15],
            chart: {
                type: 'donut',
                width: '100%',
                height: '450px',
                fontFamily: '',
            },
            states: {
                hover: {
                    filter: {
                        type: 'lighten',
                        value: 0.1, // Modifiez cette valeur pour contrôler l'effet de luminosité
                    }
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '80%',
                        labels: {
                            show: true,
                            total: {
                            show: true,
                            showAlways: false,
                            label: 'Total',
                            fontSize: '22px',
                            fontFamily: 'Helvetica, Arial, sans-serif',
                            fontWeight: 600,
                            color: '#ffffff',
                            formatter: function (w) {
                                // Calcule le total des séries
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                            }
                        },
                            name: {
                                color: 'white', // Définissez la couleur des labels des noms
                            },
                            value: {
                                color: 'white' // Définissez la couleur des labels des valeurs
                            },
                            
                        }
                        
                    }
                }
            },
            stroke: {
                show: true,
                width: 0, // Épaisseur de la bordure
                colors: ['#49243E'], // Couleur de la bordure, ici semi-transparente
            },
            legend: {
                show: true, // Activer ou désactiver la légende
                fontSize: '20px', // Ajustez la taille de la police
                position: 'left', // Placer la légende à droite
                floating: true,
                offsetY: 0, // Ajuster légèrement la position verticale
                offsetX: 200,
                labels: {
                    colors: 'white', // Ou utilisez un tableau de couleurs si vous avez plusieurs labels.
                    useSeriesColors: false,
                    width: 30
                },
                markers: {
                    width: 20, // Augmentez si vous voulez des marqueurs plus grands
                    height: 20,
                },
                itemMargin: {
                    horizontal: 20,
                    vertical: 10,  // Augmentez pour plus d'espacement vertical
                },
                onItemHover: {
                    highlightDataSeries: false,
                
                },
                
            },
            colors: ['#007F73', '#A0153E', '#27005D', '#F94C10', '#FF8333'],
            labels: ['Produit A', 'Produit B', 'Produit C', 'Produit D', 'Produit E'],
        };
        const chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    })


</script>