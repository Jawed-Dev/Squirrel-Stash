<template>
    <div class="fixed inset-0 bg-black bg-opacity-80 z-30 ">
        <div :class="`bg-main-gradient flex flex-col fixed 
        shadow-black shadow-custom-main rounded-md top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 trigger-overlay-privacy
        z-30 text-white ${width}`">
    
            <MainContainerSlot :onlyOneBtn="true" :bgMainBtn="'bg-gradient-blue'" width='w-full'
            :textBtn1="'Fermer'" titleContainer="Politique de Confidentialité" @toggleMenu="toggleMenu">
                <div class="pl-24 max-h-[65vh] overflow-y-auto text-xl 
                flex flex-col w-full rounded-[3px] my-[40px] gap-20">

                <div class="flex flex-col items-center gap-5">
                    <h2 class="text-[25px]">Politique de Confidentialité de [Nom de l'Application]</h2>
                    <p class="font-thin">Date de dernière mise à jour : [Date]</p>
                </div>
    

                <div class="flex flex-col gap-5 w-[90%]">
                    <h2 class="text-[25px]">1. Introduction</h2>
                    <p class="opacity-90 font-light">Chez [Nom de l'Application], nous comprenons l'importance de la confidentialité de nos utilisateurs. 
                        <span class="block"></span>Cette politique de confidentialité explique comment nous collectons, utilisons, protégeons, et partageons les informations personnelles des utilisateurs de notre application de gestion de budget pour particuliers.</p>
                </div>

                <div class="flex flex-col gap-5 w-[90%]">
                    <h2 class="text-[25px]">2. Informations Collectées</h2>
                    <p class="opacity-90 text-[20px] font-light">Nous collectons les informations suivantes pour fournir et améliorer notre service :</p>
                    <ul class="opacity-70 text-[18px] font-light">
                        <li>- Nom et prénom</li>
                        <li>- Adresse email</li>
                        <li>- Adresse IP</li>
                        <li>- Genre (si fourni)</li>
                        <li>- Date de naissance (si fournie)</li>
                        <li>- Mot de passe</li>
                    </ul>
                </div>

                <div class="flex flex-col gap-5 w-[90%]">
                    <h2 class="text-[25px]">3. Utilisation des Données</h2>
                    <p class="opacity-90 text-[20px] font-light">
                        Les données collectées sont uniquement utilisées pour améliorer le service offert par [Nom de l'Application]. 
                        <span class="block"></span>Nous utilisons ces informations pour aider à personnaliser et améliorer votre expérience d'utilisation de l'application.
                    </p>
                </div>

                <div class="flex flex-col gap-5 w-[90%]">
                    <h2 class="text-[25px]">4. Partage des Données</h2>
                    <p class="opacity-90 text-[20px] font-light">[Nom de l'Application] s'engage à ne pas vendre, louer ou échanger vos informations personnelles avec des tiers à des fins commerciales ou marketing. 
                        Les données collectées restent exclusivement utilisées en interne.</p>
                </div>

                <div class="flex flex-col gap-5 w-[90%]">
                    <h2 class="text-[25px]">5. Sécurité des Données</h2>
                    <p class="opacity-90 text-[20px] font-light">La sécurité de vos données est une priorité pour nous. Nous appliquons des mesures de sécurité techniques pour protéger vos informations personnelles contre l'accès non autorisé, l'altération, la divulgation ou la destruction. Vos informations personnelles sont stockées dans des environnements sécurisés et accessibles uniquement par le personnel autorisé.</p>
                </div>

                <div class="flex flex-col gap-5 w-[90%]">
                    <h2 class="text-[25px]">6. Droits des Utilisateurs</h2>
                    <p class="opacity-90 text-[20px] font-light">Vous avez le droit de demander l'accès, la rectification ou la suppression de vos données personnelles. Pour toute demande concernant vos données, veuillez nous contacter via la rubrique Support et Aide de l'application.</p>
                </div>

                <div class="flex flex-col gap-5 w-[90%]">
                    <h2 class="text-[25px]">7. Contact</h2>
                    <p class="opacity-90 text-[20px] font-light">Pour toute question ou préoccupation concernant cette politique de confidentialité ou vos données personnelles, n'hésitez pas à nous contacter via notre rubrique Support et Aide.</p>
                </div>
                    
                </div>
            </MainContainerSlot>

        </div>
    </div>
</template>

    

<script setup>
    import MainContainerSlot from '@/component/containerSlot/MainContainerSlot.vue';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';

    // props, variables..
    const props = defineProps({
        width: {default: ''},
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