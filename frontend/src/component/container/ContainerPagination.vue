<template>
    <div v-if="getCurrentPage() !== 1 && showEllipsisAfterFirstPage" class="flex items-center gap-1">
        <div @click="handlePagination(1)" 
        :class="`${showColorPagination(1)} flex justify-center w-[1.3vw] px-[10px] 
                rounded-sm font-medium shadow-black shadow-custom-lower cursor-pointer`">
        {{ 1 }}
        </div>
        <p>...</p>
    </div>

    <div v-for="(page, index) in visiblePages" :key="index" class="flex items-center gap-1">
        <div v-if="(page === 1 && !showEllipsisAfterFirstPage && totalItems >= 1)"
            @click="handlePagination(page)"
            :class="`${showColorPagination(page)} flex justify-center w-[1.3vw] px-[10px] 
                    rounded-sm font-medium shadow-black shadow-custom-lower cursor-pointer`">
            {{ page }}
        </div>

        <div v-if="(page === getTotalPages() && !showEllipsisBeforeLastPage) && getTotalPages() !== 1"
            @click="handlePagination(page)"
            :class="`${showColorPagination(page)} flex justify-center w-[1.3vw] px-[10px] 
                    rounded-sm font-medium shadow-black shadow-custom-lower cursor-pointer`">
            {{ page }}
        </div>
        
        <!-- Autres boutons -->
        <div v-if="page !== 1 && page !== getTotalPages()" @click="handlePagination(page)" 
            :class="`${showColorPagination(page)} flex justify-center w-[1.3vw] px-[10px] 
                    rounded-sm font-medium shadow-black shadow-custom-lower cursor-pointer`">
            {{ page }}
        </div>
    </div>

    <div v-if="getCurrentPage() !== getTotalPages() && showEllipsisBeforeLastPage" class="flex items-center gap-1">
        <p>...</p>
        <div @click="handlePagination(getTotalPages())"
        :class="`${showColorPagination(getTotalPages())} flex justify-center w-[1.3vw] px-[10px] 
                rounded-sm font-medium shadow-black shadow-custom-lower cursor-pointer`">
        {{ getTotalPages() }}
        </div>
    </div>
</template>

<script setup>

    import {computed} from 'vue';

    // variables, props...

    const props = defineProps({
        itemsPerPage : { default: 15 },

    });
    const maxButtons = 5;
    const totalItems = defineModel('totalItems');
    const currentPage = defineModel('currentPage');
    const totalPages = defineModel('totalPages');

    const visiblePages = computed(() => {
        let pages = [];
        let startPage = Math.max(1, currentPage.value - Math.floor(maxButtons / 2));
        let endPage = Math.min(getTotalPages(), startPage + maxButtons - 1);

        if (endPage - startPage + 1 < maxButtons) startPage = Math.max(1, endPage - maxButtons + 1);
        
        for (let i = startPage; i <= endPage; i++) {
            pages.push(i);
        }
        return pages;
    });

    const showEllipsisAfterFirstPage = computed(() => {
        const startTransition = Math.ceil(getMaxBtns() / 2);
        return getCurrentPage() > startTransition;
    });

    const showEllipsisBeforeLastPage = computed(() => {
        let startPage = currentPage.value - Math.floor(maxButtons / 2);
        startPage = Math.max(startPage, 1); 
        let endPage = startPage + maxButtons - 1;
        endPage = Math.min(endPage, getTotalPages()); 
        return endPage < getTotalPages();
    });

    function showColorPagination(index) {
        return (getCurrentPage() === index) ? 'bg-main-bg' : 'bg-main-blue';
    }
    const getCurrentPage = () => currentPage.value;
    const getMaxBtns = () => maxButtons;
    function getTotalPages() {
        return Math.ceil(totalItems.value / props.itemsPerPage);
    }
    function handlePagination(index) {
        currentPage.value = index;
    }
    
</script>