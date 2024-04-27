<template>
    <div class="w-headerWidth top-[20px] left-[60px] fixed flex flex-col justify-center gap-5 h-[calc(100vh-50px)] items-center
            bg-[linear-gradient(to_top_left,_#101010_80%,_#ffff_350%)] border border-[#38393b]  rounded-md
             shadow-[#38393b] shadow-[0_0_10px_#38393b]"  style="text-shadow: 4px 4px 3px rgba(0,0,0,1); ">


            <icon-nav @click="handleClickIcon('home')"/>
            
            <icon-nav @click="handleClickIcon('home')"/>
            
            <icon-nav @click="handleClickIcon('home')"/>

            <icon-nav @click="handleClickIcon('home')"/>

            <icon-nav @click="handleClickIcon('home')"/>

    </div>

    <!-- <svg class="text-white p-2 mr-3 cursor-pointer w-[30px]" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path d="M0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM281 385c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l71-71L136 280c-13.3 0-24-10.7-24-24s10.7-24 24-24l182.1 0-71-71c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0L393 239c9.4 9.4 9.4 24.6 0 33.9L281 385z"/>
                </svg> -->
</template>


<script setup>
    import { useRouter } from 'vue-router';
    import iconNav from '../icons/nav/IconNav.vue';

    const router = useRouter();

    function handleClickIcon(request) {

        switch (request) {
            case 'home': {
                //alert(request);

                router.push('/login');
                break;
            }
                
        
            default:
                break;
        }
    }

</script>


