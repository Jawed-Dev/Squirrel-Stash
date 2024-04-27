<template>
    <svg 
        class="text-white cursor-pointer w-[25px] flex rounded-md 
        transition-transform hover:translate-y-[-5px] duration-[0.5s]" 
        style="filter: drop-shadow(8px 8px 5px rgba(0, 0, 0, 0.35));" 
        
        fill="currentColor" filter="url(#dropshadow)"  xmlns="http://www.w3.org/2000/svg" :viewBox="pathSvgIcon('home').viewBox">
        <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc. -->
        <path :d="pathSvgIcon('home').path"/>
    </svg>
</template>


<script setup>

    import pathSvgIcon from '../../../svgs/pathIcons.js';

</script>