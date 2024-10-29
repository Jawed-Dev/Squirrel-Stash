<template>
    <div class="fixed inset-0 bg-black bg-opacity-80 z-20">
        <div :class="`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-20 text-white rounded-[3px]
                shadow-main bg-main-gradient w-full lg:w-[1024px] font-main`">
    
            <ContainerSlotOverlay :onlyOneBtn="true" :bgMainBtn="'bg-gradient-blue'" width='w-full'
            :textBtn1="'Fermer'" titleContainer="Mentions légales" @toggleMenu="toggleMenu">

                <div class="max-h-[calc(80vh-56px)] overflow-y-auto">
                    <div class="flex flex-col items-center gap-5 mt-10">
                        <h2 class="text-custom-blue text-center text-xl">Mentions légales de Squirrel Stash</h2>
                    </div>
    
                    <div class="px-5 sm:px-10 md:px-12 w-full flex justify-center mt-5">
                        <div class="flex flex-col w-full rounded-[3px] py-10 gap-20">
    
                            <div class="flex flex-col gap-5 w-full">
                                <h2 class="text-custom-blue text-xl">1. Éditeur du site</h2>
                                <p class="opacity-95 font-light">Le site Squirrel Stash est édité par Jawed Bouta, domicilié au 20 boulevard Foch, 84000 Avignon.
                                <span class="block"></span>Ce site n'est pas enregistré en tant qu'entreprise.</p>
                                <p class="opacity-95 font-light"><span class="block"></span>Contact :
                                <span class="block"></span>Téléphone : <span class="font-normal text-main-blue">06 52 55 02 89</span>
                                <span class="block"></span>Email : <span class="font-normal text-main-blue">bouta.jawed@gmail.com</span></p>
                            </div>
        
                            <div class="flex flex-col gap-5 w-full">
                                <h2 class="text-custom-blue text-xl">2. Hébergement :</h2>
                                <p class="opacity-95 font-light">Le site est hébergé par <span class="font-normal text-main-blue">OVH SAS</span>, société par actions simplifiée au capital de 10 069 020 €, dont le siège social est situé au 2 rue Kellermann, 59100 Roubaix, France.</p>
                                <p class="opacity-95 font-light">Numéro de téléphone : 1007</p>
                            </div>
        
                            <div class="flex flex-col gap-5 w-full">
                                <h2 class="text-custom-blue text-xl">3. Propriété intellectuelle</h2>
                                <p class="opacity-95 font-light">Le contenu de ce site, incluant, sans s'y limiter, les images, textes, vidéos, logos et icônes sont la propriété exclusive de Squirrel Stash à l'exception des marques, logos ou contenus appartenant à d'autres sociétés partenaires ou auteurs. </p>
                                <p class="opacity-95 font-light">La reproduction, la distribution, la modification, l'adaptation, la retransmission ou la publication, même partielle, de ces différents éléments est strictement interdite sans l'expression écrite préalable de Squirrel Stash. </p>
                                <p class="opacity-95 font-light">Cette représentation ou reproduction, par quelque procédé que ce soit, constitue une contrefaçon sanctionnée par les articles <span class="font-normal text-main-blue">L.335-2</span> et suivants du Code de la propriété intellectuelle. </p>
                            
                            </div>
        
                            <div class="flex flex-col gap-5 w-full">
                                <h2 class="text-custom-blue text-xl">4. Données personnelles et confidentialité :</h2>
                                <p class="opacity-95 font-light">Les détails sur la collecte, l'utilisation et la protection des données personnelles des utilisateurs sont disponibles dans notre <span @click="openOverlay('privacy')" class="hover:text-blue-300 text-main-blue font-normal cursor-pointer">Politique de Confidentialité</span>.</p>
                            </div>
        
                            <div class="flex flex-col gap-5 w-full">
                                <h2 class="text-custom-blue text-xl">5. Règles d'utilisation :</h2>
                                <p class="opacity-95 font-light">Les conditions régissant l'utilisation de ce site sont détaillées dans nos <span @click="openOverlay('rules')" class="hover:text-blue-300 text-main-blue font-normal cursor-pointer">Règles d'Utilisation</span>.</p>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </ContainerSlotOverlay>
        </div>
        <OverlayPrivacy v-if="isotherOverlaysActive.privacy" v-model="isotherOverlaysActive.privacy" />
        <OverlayUserRules v-if="isotherOverlaysActive.rules" v-model="isotherOverlaysActive.rules" />
    </div>
</template>

    

<script setup>
    import ContainerSlotOverlay from '@/components/containerSlot/ContainerSlotOverlay.vue';
    import { reactive, defineAsyncComponent } from 'vue';
    const OverlayPrivacy = defineAsyncComponent(() => import('@/components/overlay/OverlayPrivacy.vue'));
    const OverlayUserRules = defineAsyncComponent(() => import('@/components/overlay/OverlayUserRules.vue'));

    // props, variables..
    const props = defineProps({
        infoTransaction: {default: []},
    });

    const isOverlayActive = defineModel();
    const isotherOverlaysActive = reactive({
        privacy: false,
        rules: false
    });

    function toggleMenu(request) {
        switch(request) {
            case 'cancel': {
                closeOverlay();
                break;
            } 
        }
    }

    async function openOverlay(request) {
        switch(request) {
            case 'privacy' : {
                isotherOverlaysActive.privacy = true;
                break;
            }
            case 'rules' : {
                isotherOverlaysActive.rules = true;
                break;
            }

        }
    }

    function closeOverlay() {
        isOverlayActive.value = false;
    }

</script>