<template>
    
    <div style="position: relative; height:350px; width:100%">
        <canvas class="mt-5" ref="canvas"></canvas>
        <button class="absolute right-3 top-[-80px] font-light" @click="downloadChart">Télécharger</button>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import Chart from 'chart.js/auto';

// props, variables
const props = defineProps({
    dataTransaction: { default: []},
    colorsGraph: { default: () => {}},
    limitWidth: {default: undefined}
});

const dataLoaded = ref(false);

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

    dataLoaded.value = true;
});


watch(() => props.dataTransaction, (newData) => {
    updateChartData(chartInstance, newData);
}, { deep: true });


function updateChartData(chart, newData) {
    if (!chartInstance) return;  

    const ctx = canvas.value.getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, props.colorsGraph.color1);
    gradient.addColorStop(1, props.colorsGraph.color2);

    chartInstance.data.datasets.forEach((dataset) => {
        dataset.borderColor = 'white';
        //dataset.borderColor = props.colorsGraph.borderColor;
        dataset.backgroundColor = gradient;
    });
    
    chart.data.labels = newData.map(row => row.labels);
    chart.data.datasets.forEach((dataset) => {
        dataset.data = newData.map(row => row.total_amount);
    });
    chart.update();
}

function downloadChart() {
    if (canvas.value) {
        const link = document.createElement('a');
        link.href = canvas.value.toDataURL('image/png');
        link.download = 'graphique.png';
        link.click();
    }
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
                        return "Montant: " + tooltipItem.raw + ' €';
                    }
                }
            }
        },
        scales: {
            x: {
                grid: { display: true, color: '' },
                ticks: { stepSize: 5, color: 'white' },
            },
            y: {
                beginAtZero: true,
                
                ticks: {
                    stepSize: 0,
                    color: 'white',
                    callback: value => `${value} €`
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
