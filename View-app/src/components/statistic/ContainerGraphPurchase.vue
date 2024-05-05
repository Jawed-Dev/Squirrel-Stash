<template>
    <section 
        class="w-[100%] pl-5 mt-custom-margin-main bg-main-gradient text-white 
        rounded-md
        shadow-custom-gray-dark shadow-custom-main">
        
        <h2 class="py-3 text-[25px] font-extralight">Achats du mois</h2>
        
        <div class="flex border-b border-main-color border w-fit rounded-md">
            <p class="px-3 py-1 rounded-md cursor-pointer" :class="[stateTabPurchase ? 'bg-[#3358f4]' : '']" @click="handleStateTab(true)" >Achats</p>
            <p class="px-3 py-1 rounded-md cursor-pointer" :class="[stateTabPurchase ? '' : 'bg-[#3358f4]']" @click="handleStateTab(false)" >Paiement réccurent</p>
        </div>


        <div class="flex justify-center py-[20px]" id="chart">

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
    

    onMounted(() => {
         // Fonction pour générer les étiquettes des jours du mois en cours
            function generateDaysOfMonthLabels() {
                const currentDate = new Date();
                const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
                const labels = [];
                
                for (let day = 1; day <= daysInMonth; day++) {
                    labels.push(`${day}`);
                }
                
                return labels;
            }

            function generateRandomData() {
        const data = [];

        for (let i = 0; i < 10+1; i++) {
            
            const randomValue = Math.floor(Math.random() * 201); // Génère une valeur aléatoire entre 0 et 200
            (i === 0) ? data.push(0) : data.push(randomValue);
        }

        return data;
    }

   

        const daysOfMonthLabels = generateDaysOfMonthLabels();
        const data = generateRandomData();

        const options = {
            series: [{
          name: 'series1',
          data: data
        }],
          chart: {
            toolbar: {
            show: true, // Afficher la barre d'outils
            tools: {
                download: true, // Activer le bouton de téléchargement
                selection: false, // Désactiver le bouton de sélection
                zoom: false, // Désactiver le bouton de zoom
                zoomin: false, // Désactiver le bouton de zoom avant
                zoomout: false, // Désactiver le bouton de zoom arrière
                pan: false, // Désactiver le bouton de panoramique
                reset: false, // Désactiver le bouton de réinitialisation
                customIcons: [] // Vous pouvez également fournir vos propres icônes personnalisées si nécessaire
            },
            autoSelected: 'download', // Sélectionne automatiquement le bouton de téléchargement
            colors: '#3358f4',
        },
          height: 300,
          width: '100%',
          type: 'area'
        },
        grid: {
            show: false,      // you can either change hear to disable all grids
            xaxis: {
                lines: {
                    show: true  //or just here to disable only x axis grids
                },
                
            },  
            yaxis: {
                lines: { 
                    show: false  //or just here to disable only y axis
                }
            },   
        },
        zoom: {
            enabled: false
        },
        colors: ['#00f2c3'], // Couleur de la courbe
        dataLabels: {
          enabled: true
        },
        stroke: {
          curve: 'smooth'
        },

        fill: {  // Ajout de la configuration de remplissage en dégradé
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0,
                opacityTo: 0.1,
                stops: [0, 100],
                colorStops: [
                    {
                        offset: 0,
                        color: '#3358f4',  // Couleur de départ du dégradé
                        opacity: 0.5
                    },
                    {
                        offset: 100,
                        color: "#3358f4",  // Couleur de fin du dégradé
                        opacity: 0
                    }
                ]
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: 'white',  // Couleur des labels de l'axe Y
                    fontSize: '14px',  // Taille de la police des labels de l'axe Y
                    fontFamily: '',  // Famille de la police (optionnel)
              
                    
                }
            }
        },
        xaxis: {
            categories: daysOfMonthLabels,
            labels: {
                style: {
                    colors: 'white',  // Couleur des labels de l'axe Y
                    fontSize: '16px',  // Taille de la police des labels de l'axe Y
                    fontFamily: ''  // Famille de la police (optionnel)
                }
            },
            axisBorder: {
                show: false, // Cela désactive la barre de l'axe X
            },
        },
        tooltip: {
            enabled: false,
            fillSeriesColor: false,
            x: {
                format: 'dd/MM'
            },
            fixed: {
                enabled: false,
                position: 'topRight',
                offsetX: 0,
                offsetY: 0,
            },
            style: {
                fontSize: '10px', // Taille de la police du texte du tooltip
                fontFamily: 'Arial, sans-serif', // Famille de police du texte du tooltip
                colors: '#ffffff'
            },
        },
        };
        const chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    })


</script>