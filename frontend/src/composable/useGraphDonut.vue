<template>
    <div class="relative" style="position: relative; height:350px; width:100%">
        <canvas class="mt-5" ref="canvas"></canvas>
        <ButtonDownloadChart top="top-[-53px]" :canvas="canvas" />
    </div>
</template>

<script setup>
import { ref, onMounted, watch,computed } from 'vue';
import Chart from 'chart.js/auto';
import ButtonDownloadChart from '@/component/button/ButtonDownloadChart.vue';

const props = defineProps({
    dataTransaction: { default: []},
    colorsGraph: { default: () => {}},
    typeTransaction: {default: false}
});

const colorsDonut = computed(() => {
    return (props.typeTransaction) ? [
            '#EF5A6F', 
            '#FFEADD', 
            '#D4BDAC', 
            '#536493',  
            '#D4BDAC', 
        ] 
            : 
        [
            '#E68369', 
            '#03346E', 
            '#D4BDAC', 
            '#E2E2B6',  
            '#6EACDA', 
        ];
});

const canvas = ref(null);
let chartInstance = null;

onMounted(() => {
    const gradient = canvas.value.getContext('2d').createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, props.colorsGraph.color1);
    gradient.addColorStop(1, props.colorsGraph.color2);
    
    chartInstance = new Chart(canvas.value, {
        type: 'doughnut',
        data: {
            datasets: [{
                borderWidth: 1.5,
                fill: true,
                label: 'Graphique',
                data: [],
                tension: 0.3
            }],
        },
        options: getChartOptions()
    });

    
});

watch(() => props.dataTransaction, (newData) => {
    updateChartData(chartInstance, newData);
}, { deep: true });


function updateChartData(chart, newData) {
    if (!chartInstance) return;  

    const ctx = canvas.value.getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, props.colorsGraph.color1);
    gradient.addColorStop(1, props.colorsGraph.color2);

    chartInstance.data.datasets.forEach((dataset) => {
        dataset.borderColor = 'white';
        dataset.backgroundColor = colorsDonut.value;
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
            legend: {
                display: true,
                position: 'left',
                labels: {
                    boxWidth: 50, 
                    padding: 30, 
                    color: 'white',
                    borderWidth: 1,
                    font: {
                        size: 15,
                    },
                },
            },
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
                display: false,
                grid: { display: true, color: '' },
                ticks: { stepSize: 5, color: 'white' },
            },
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 0,
                    color: 'white',
                    callback: value => ``
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
