<template>
    <div class="w-[170px] top-0 left-0 bg-main-blue rounded-r-xl text-lg h-[100vh] fixed flex flex-col justify-center gap-5"  style="text-shadow: 4px 4px 3px rgba(0,0,0,1); ">
            <div @click="handleClickIcon('home')" class="flex transition-transform  hover:translate-y-[-5px] duration-[0.5s] hover:bg-black items-center rounded-md cursor-pointer">
                <svg style="filter: drop-shadow(8px 8px 5px rgba(0, 0, 0, 0.35));" class="text-white p-2 mr-3" 
                    
                    width="43" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"> 
                    <!-- !Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc. -->
                    <path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/>

                </svg> 
                <p class="text-white">Accueil</p>
            </div>
            
            <div class="flex transition-transform  hover:translate-y-[-10px] duration-[0.5s] hover:bg-black items-center rounded-md cursor-pointer">
                <svg style="filter: drop-shadow(8px 8px 5px rgba(0, 0, 0, 0.35));" class="text-white p-2 mr-3" width="40px" fill="currentColor" filter="url(#dropshadow)"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc. -->
                    <path d="M40 48C26.7 48 16 58.7 16 72v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V72c0-13.3-10.7-24-24-24H40zM192 64c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zM16 232v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V232c0-13.3-10.7-24-24-24H40c-13.3 0-24 10.7-24 24zM40 368c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V392c0-13.3-10.7-24-24-24H40z"/>
                </svg>
                <p class="text-white">Transactions</p>
            </div>
            <!-- style="text-shadow: 4px 4px 3px rgba(0,0,0,0.25); -->
            <div class="flex transition-transform  hover:translate-y-[-10px] duration-[0.5s] hover:bg-black items-center rounded-md cursor-pointer">
                <svg style="filter: drop-shadow(8px 8px 5px rgba(0, 0, 0, 0.35));" width="40px" class="text-white p-2 mr-3"  fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc. -->
                        <path d="M160 80c0-26.5 21.5-48 48-48h32c26.5 0 48 21.5 48 48V432c0 26.5-21.5 48-48 48H208c-26.5 0-48-21.5-48-48V80zM0 272c0-26.5 21.5-48 48-48H80c26.5 0 48 21.5 48 48V432c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V272zM368 96h32c26.5 0 48 21.5 48 48V432c0 26.5-21.5 48-48 48H368c-26.5 0-48-21.5-48-48V144c0-26.5 21.5-48 48-48z"/>
                </svg>
                <p class="text-white">Détails</p>
            </div>

            <div class="flex transition-transform  hover:translate-y-[-10px] duration-[0.5s] hover:bg-black items-center rounded-md cursor-pointer">
                <svg style="filter: drop-shadow(8px 8px 5px rgba(0, 0, 0, 0.35));" width="40px" class="text-white p-2 mr-3" fill="currentColor"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc. -->
                    <path d="M224 0c-17.7 0-32 14.3-32 32V51.2C119 66 64 130.6 64 208v18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416H416c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8V208c0-77.4-55-142-128-156.8V32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z"/>
                    
                </svg>
                <p class="text-white">Détails</p>
            </div>

            <div class="flex transition-transform  hover:translate-y-[-10px] duration-[0.5s] hover:bg-black items-center rounded-md cursor-pointer">
            <svg style="filter: drop-shadow(8px 8px 5px rgba(0, 0, 0, 0.35));" width="40px" class="text-white p-2 mr-3" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc. -->
                <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
            </svg>
            <p class="text-white mr-3">Utilisateur</p>

            <svg class="text-white" width="20px" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc. -->
                <path d="M0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM281 385c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l71-71L136 280c-13.3 0-24-10.7-24-24s10.7-24 24-24l182.1 0-71-71c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0L393 239c9.4 9.4 9.4 24.6 0 33.9L281 385z"/>
            </svg>
            
        </div>

            
    </div>
</template>


<script setup>
    import { useRouter } from 'vue-router';
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


