<template>
    <div class="relative min-h-[350px] w-full">
        <canvas class="mt-5" ref="canvas"></canvas>
        <ButtonDownloadChart v-show="!isDataEmpty" :canvas="canvas" />
    </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import Chart from 'chart.js/auto';
import ButtonDownloadChart from '@/component/button/ButtonDownloadChart.vue';
import { formatFloatAsString } from '@/composable/useMath';


// props, variables
const props = defineProps({
    dataTransaction: { default: []},
    colorsGraph: { default: () => {}},
    limitWidth: {default: undefined},
});

//const dataLoaded = ref(false);
const canvas = ref(null);
let chartInstance = null;

// life cycle, fonctions
onMounted(() => {
    const gradient = canvas.value.getContext('2d').createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, props.colorsGraph.color1);
    gradient.addColorStop(1, props.colorsGraph.color2);

    chartInstance = new Chart(canvas.value, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                backgroundColor: gradient,
                borderColor: props.colorsGraph.borderColor,
                borderWidth: 1.5,
                fill: true,
                label: 'Graphique',
                data: [],
                tension: 0.3,
                barThickness: props.limitWidth
            }],
        },
        options: getChartOptions()
    });
});

watch(() => props.dataTransaction, (newData) => {
    if(!newData) return;
    updateChartData(chartInstance, newData);    
}, { deep: true });



const isDataEmpty = computed( () => {
    let dataEmpty = true;
    props.dataTransaction.forEach(element => {
        if(element.total_amount > 0) dataEmpty = false;
    });
    return dataEmpty;
});

function updateChartData(chart, newData) {
    if (!chart) return;  

    const ctx = canvas.value.getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, props.colorsGraph.color1);
    gradient.addColorStop(1, props.colorsGraph.color2);

    chart.data.datasets.forEach((dataset) => {
        dataset.borderColor = 'white';
        dataset.backgroundColor = gradient;
    });
    
    chart.data.labels = newData.map(row => row.labels);
    chart.data.datasets.forEach((dataset) => {
        dataset.data = newData.map(row => row.total_amount);
    });
    chart.update();
}


function getChartOptions() {
    return {
        responsive: true,
        maintainAspectRatio: false,
        
        plugins: {
            legend: { display: false },
            tooltip: {
                displayColors: false,
                callbacks: {
                    label: function(tooltipItem) {
                        return "Montant: " + formatFloatAsString(tooltipItem.raw) + ' €';
                    }
                },
                
            }
        },
        scales: {
            x: {
                grid: { display: true, color: '' },
                ticks: { 
                    stepSize: 5, 
                    color: 'white',
                    font: {
                        size: 13
                    },
                    
                },
            },
            y: {
                beginAtZero: true,
                
                ticks: {
                    stepSize: 0,
                    color: 'white',
                    font: {
                        size: 13
                    },

                    callback: value => `${value} €`,
                    
                },
                grid: {
                    display: true,
                    color: '#2c3e5080',
                }
            }
        }
    };
}
</script>
