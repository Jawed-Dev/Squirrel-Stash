<template>
    <svg 
        class="text-white w-[50px] cursor-pointer rounded-md py-2  px-3
        transition-transform hover:translate-y-[-5px] duration-[0.5s]" 

        :fill="[pageState() ? '#1b1e33' : 'currentColor']" filter="url(#dropshadow)"  xmlns="http://www.w3.org/2000/svg" :viewBox="pathSvgIcon(svgIcon).viewBox">
        <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc. -->
        <path :d="pathSvgIcon(svgIcon).path"/>
    </svg>
</template>

<!-- style="filter: drop-shadow(8px 8px 5px rgba(0, 0, 0, 0.35));"  -->
<!-- :class="[pageState() ? 'bg-custom-gray-2' : '']" -->

<script setup>

    import pathSvgIcon from '../../../svgs/pathIcons';
    


    

    const props = defineProps(['svgIcon']);

    function pageState() {
        const pathname = window.location.pathname;
        const partOfUrl = pathname.substring(1);

        if(props.svgIcon === partOfUrl) return true;
        return false;
    }

</script>