<template>
    <div class="pl-5 bg-main-gradient text-white rounded-md gradient-border ">
        <h2 class="py-3 text-[25px] font-extralight">Achats du mois</h2>

        <ToggleButton v-model:typeTransaction="typeTransaction" :text1="'Achat'" :text2="'Prélèvements'" />

        <useGraphBarLoad :colorsGraph="(!typeTransaction) ? colorPurchases : colorReccurings" :dataTransaction="(!typeTransaction) ? dataPurchases : dataReccurings" />
        

    </div>
</template>

<script setup>
    import { ref, watch } from 'vue';
    import ToggleButton from '../../button/ToggleButton.vue';
    import useGraphBarLoad from '@/composables/useGraphBarLoad.vue';

    // functions, props ...
    const typeTransaction = ref(false);

    const colorPurchases = {
        color1: '#3358f4',
        color2: '#1d8cf810',
        borderColor: '#0098f0'
    };

    const colorReccurings = {
        color1: '#89216B',
        color2: '#DA445310',
        borderColor: '#ec250d'
    }

    watch(typeTransaction, (newVal, oldVal) => {
        // alert('changement');
    });

    const dataPurchases = [
        { day: 1, count: 10 },
        { day: 2, count: 250 },
        { day: 3, count: 220 },
        { day: 4, count: 180 },
        { day: 5, count: 0 },
        { day: 6, count: 30 },
        { day: 7, count: 35 },
        { day: 8, count: 40 },
        { day: 9, count: 208 },
        { day: 10, count: 26 },
        { day: 11, count: 30 },
        { day: 12, count: 34 },
        { day: 13, count: 0 },
        { day: 14, count: 400 },
        { day: 15, count: 0 },
        { day: 16, count: 29 },
        { day: 17, count: 45 },
        { day: 18, count: 0 },
        { day: 19, count: 47 },
        { day: 20, count: 52 },
        { day: 21, count: 55 },
        { day: 22, count: 0 },
        { day: 23, count: 150 },
        { day: 24, count: 48 },
        { day: 25, count: 45 },
        { day: 26, count: 40 },
        { day: 27, count: 35 },
        { day: 28, count: 30 },
        { day: 29, count: 25 },
        { day: 30, count: 20 },
        { day: 31, count: 15 }
    ];

    const dataReccurings = [
        { day: 1, count: 200 },
        { day: 2, count: 500 },
        { day: 3, count: 0 },
        { day: 4, count: 0 },
        { day: 5, count: 0 },
        { day: 6, count: 0 },
        { day: 7, count: 35 },
        { day: 8, count: 30 },
        { day: 9, count: 0 },
        { day: 10, count: 0 },
        { day: 11, count: 120 },
        { day: 12, count: 0 },
        { day: 13, count: 0 },
        { day: 14, count: 30 },
        { day: 15, count: 0 },
        { day: 16, count: 0 },
        { day: 17, count: 0 },
        { day: 18, count: 0 },
        { day: 19, count: 0 },
        { day: 20, count: 200 },
        { day: 21, count: 0 },
        { day: 22, count: 0 },
        { day: 23, count: 0 },
        { day: 24, count: 0 },
        { day: 25, count: 0 },
        { day: 26, count: 0 },
        { day: 27, count: 0 },
        { day: 28, count: 0 },
        { day: 29, count: 0 },
        { day: 30, count: 0 },
        { day: 31, count: 0 }
    ];


</script>