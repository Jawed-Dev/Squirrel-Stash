<template>
    <div style="position: relative; height:350px; width:100%">
        <canvas class="mt-5" ref="canvas"></canvas>
        <ButtonDownloadChart :canvas="canvas" />
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import Chart from 'chart.js/auto';
import ButtonDownloadChart from '@/component/button/ButtonDownloadChart.vue';

const props = defineProps({
    dataTransaction: { default: []},
    colorsGraph: { default: () => {}}
});

const canvas = ref(null);
let chartInstance = null;


onMounted(() => {
    const gradient = canvas.value.getContext('2d').createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, props.colorsGraph.color1);
    gradient.addColorStop(1, props.colorsGraph.color2);

    chartInstance = new Chart(canvas.value, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                backgroundColor: gradient,
                borderColor: props.colorsGraph.borderColor,
                borderWidth: 2,
                fill: true,
                label: 'Graphique',
                data: [],
                tension: 0.1
            }],
        },
        options: getChartOptions()
    });
});

watch(() => props.dataTransaction, (newData) => {
        console.log('newData',newData)
        updateChartData(chartInstance, newData);
}, { deep: true });


function updateChartData(chart, newData) {
    if (!chartInstance) return;  

    const ctx = canvas.value.getContext('2d');
    const gradient = ctx.createLinearGradient(0, 300, 0, 300);
    gradient.addColorStop(0, props.colorsGraph.color1);
    gradient.addColorStop(1, props.colorsGraph.color2);

    // Mettre à jour le gradient dans le dataset du graphique
    chartInstance.data.datasets.forEach((dataset) => {
        dataset.borderColor = props.colorsGraph.borderColor;
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
