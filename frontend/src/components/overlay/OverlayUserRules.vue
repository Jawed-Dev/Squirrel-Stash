<template>
    <div class="fixed inset-0 bg-black bg-opacity-80 z-30">
        <div :class="`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-30 text-white rounded-[3px]
                 shadow-main bg-main-gradient
                w-full lg:w-[1024px]  font-main`">
    
            <MainContainerSlot :bgMainBtn="'bg-gradient-blue'" width='w-full'
            :onlyOneBtn="true" :textBtn1="'Fermer'" titleContainer="Conditions d'utilisation de Squirrel Stash" @toggleMenu="toggleMenu">
                
            <div class=" max-h-[75vh] overflow-y-auto my-2">

                <div class="px-3 md:px-12 w-full flex justify-center">
    
                    <div class="flex flex-col w-full rounded-[3px] py-5 gap-20">
        
                        <div class="flex flex-col gap-5 w-full">
                            <h2 class="text-custom-blue text-xl">1. Conditions d'Utilisation</h2>
                            <p class="opacity-95 font-light text">Chez Squirrel Stash, nous nous engageons à fournir un service de qualité tout en respectant les droits de nos utilisateurs. 
                                Ces conditions régissent votre utilisation de notre application et en utilisant notre service, vous acceptez ces termes.</p>
                        </div>
    
                        <div class="flex flex-col gap-5 w-full">
                            <h2 class="text-custom-blue text-xl">2. Accès et utilisation du service</h2>
                            <p class="opacity-95 font-light text">Vous êtes autorisé à utiliser notre service uniquement conformément à ces conditions et à toute loi applicable. Toute utilisation abusive ou non autorisée est strictement interdite.</p>
                        </div>
    
                        <div class="flex flex-col gap-5 w-full">
                            <h2 class="text-custom-blue text-xl">3. Droits de propriété intellectuelle</h2>
                            <p class="opacity-95 font-light text">Tous les contenus présents sur l'application, y compris les textes, graphiques, logos, images, ainsi que leur sélection et disposition, sont la propriété de Squirrel Stash ou de ses fournisseurs de contenu et sont protégés par les lois sur la propriété intellectuelle.</p>
                        </div>
    
                        <div class="flex flex-col gap-5">
                            <h2 class="text-custom-blue text-xl">4. Limitation de responsabilité</h2>
                            <p class="opacity-95 font-light text">Squirrel Stash ne sera pas responsable des dommages indirects résultant de l'utilisation ou de l'impossibilité d'utiliser notre service.</p>
                        </div>
    
                        <div class="flex flex-col gap-5 w-full">
                            <h2 class="text-custom-blue text-xl">5. Modifications des termes</h2>
                            <p class="opacity-95 font-light text">Nous nous réservons le droit de modifier ces conditions à tout moment. Votre utilisation continue du service après de telles modifications constitue votre acceptation des nouvelles conditions.</p>
                        </div>
    
                        <div class="flex flex-col gap-5 w-full">
                            <h2 class="text-custom-blue text-xl">6. Contact</h2>
                            <p class="opacity-95 font-light text">Pour toute question concernant ces conditions, veuillez contacter notre support via la rubrique Support et Aide de l'application.</p>
                        </div>
                    
                    </div>
                </div>
            </div>
            </MainContainerSlot>

        </div>
    </div>
</template>

    

<script setup>
    import MainContainerSlot from '@/components/containerSlot/MainContainerSlot.vue';
    import useClickOutside from '@/composables/useClickOutSide';
    import useEscapeKey from '@/composables/useEscapeKey';

    // props, variables..
    const props = defineProps({
        width: {default: ''},
        infoTransaction: {default: []},
    });

    const isOverlayActive = defineModel();

    // useEscapeKey(isOverlayActive, () => {
    //     isOverlayActive.value = false;
    // });

    // useClickOutside( '.trigger-overlay-rules', isOverlayActive, () => {
    //     isOverlayActive.value = false;
    // },);

    async function toggleMenu(request) {
        switch(request) {
            case 'cancel': {
                closeOverlay();
                break;
            }
        }
    }

    function closeOverlay() {
        isOverlayActive.value = false;
    }

</script>