<template>
    <div class="fixed inset-0 bg-black bg-opacity-80 z-20">
        <div :class="`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-30 text-white rounded-[3px]
                shadow-main bg-main-gradient w-full lg:w-[1024px] font-main`">
    
            <ContainerSlotOverlay :onlyOneBtn="true" :bgMainBtn="'bg-gradient-blue'" width='w-full'
            :textBtn1="'Fermer'" titleContainer="Politique de Confidentialité" @toggleMenu="toggleMenu">

                <div class="max-h-[calc(80vh-56px)] overflow-y-auto">
                    <div class="flex flex-col items-center gap-5 mt-10">
                        <h2 class="text-custom-blue text-center text-xl">Politique de Confidentialité de Squirrel Stash</h2>
                    </div>
    
                    <p class="text-center font-light opacity-95 my-3">Date de dernière mise à jour : 08/09/2024</p>
    
                    <div class="px-5 sm:px-10 md:px-12 w-full flex justify-center">
                        <div class="flex flex-col w-full rounded-[3px] py-10 gap-20">
    
                            <div class="flex flex-col gap-5 w-full">
                                <h2 class="text-custom-blue text-xl">1. Introduction</h2>
                                <p class="opacity-95 font-light">Chez Squirrel Stash, nous comprenons l'importance de la confidentialité de nos utilisateurs. 
                                    <span class="block"></span>Cette politique de confidentialité explique comment nous collectons, utilisons, protégeons, et partageons les informations personnelles des utilisateurs de notre application de gestion de budget pour particuliers.</p>
                            </div>
        
                            <div class="flex flex-col gap-5 w-full">
                                <h2 class="text-custom-blue text-xl">2. Informations Collectées</h2>
                                <p class="opacity-95 font-light">Nous collectons les informations suivantes pour fournir et améliorer notre service :</p>
                                <ul class="font-light opacity-80">
                                    <li>- Nom et prénom</li>
                                    <li>- Adresse email</li>
                                    <li>- Adresse IP</li>
                                    <li>- Genre (si fourni)</li>
                                    <li>- Date de naissance (si fournie)</li>
                                    <li>- Mot de passe</li>
                                </ul>
                            </div>
        
                            <div class="flex flex-col gap-5 w-full">
                                <h2 class="text-custom-blue text-xl">3. Utilisation des Données</h2>
                                <p class="opacity-95 font-light">
                                    Les données collectées sont uniquement utilisées pour améliorer le service offert par Squirrel Stash. 
                                    <span class="block"></span>Nous utilisons ces informations pour aider à personnaliser et améliorer votre expérience d'utilisation de l'application.
                                </p>
                            </div>
        
                            <div class="flex flex-col gap-5 w-full">
                                <h2 class="text-custom-blue text-xl">4. Partage des Données</h2>
                                <p class="opacity-95 font-light">Squirrel Stash s'engage à ne pas vendre, louer ou échanger vos informations personnelles avec des tiers à des fins commerciales ou marketing. 
                                    Les données collectées restent exclusivement utilisées en interne.</p>
                            </div>
        
                            <div class="flex flex-col gap-5 w-full">
                                <h2 class="text-custom-blue text-xl">5. Sécurité des Données</h2>
                                <p class="opacity-95 font-light">La sécurité de vos données est une priorité pour nous. Nous appliquons des mesures de sécurité techniques pour protéger vos informations personnelles contre l'accès non autorisé, l'altération, la divulgation ou la destruction. Vos informations personnelles sont stockées dans des environnements sécurisés et accessibles uniquement par le personnel autorisé.</p>
                            </div>
        
                            <div class="flex flex-col gap-5 w-full">
                                <h2 class="text-custom-blue text-xl">6. Droits des Utilisateurs</h2>
                                <p class="opacity-95 font-light">Vous avez le droit de demander l'accès, la rectification ou la suppression de vos données personnelles. Pour toute demande concernant vos données, veuillez nous contacter via la rubrique Support et Aide de l'application.</p>
                            </div>
        
                            <div class="flex flex-col gap-5 w-full">
                                <h2 class="text-custom-blue text-xl">7. Contact</h2>
                                <p class="opacity-95 font-light">Pour toute question ou préoccupation concernant cette politique de confidentialité ou vos données personnelles, n'hésitez pas à nous contacter via notre rubrique Support et Aide.</p>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </ContainerSlotOverlay>

        </div>
    </div>
</template>

    

<script setup>
    import ContainerSlotOverlay from '@/components/containerSlot/ContainerSlotOverlay.vue';
    import useClickOutside from '@/composables/useClickOutSide';
    import useEscapeKey from '@/composables/useEscapeKey';

    // props, variables..
    const props = defineProps({
        infoTransaction: {default: []},
    });

    const isOverlayActive = defineModel();

    // useEscapeKey(isOverlayActive, () => {
    //     isOverlayActive.value = false;
    // });

    // useClickOutside( '.trigger-overlay-privacy', isOverlayActive, () => {
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