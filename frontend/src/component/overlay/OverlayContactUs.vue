<template>
    <div class="fixed inset-0 bg-black bg-opacity-80 z-30">
        <div :class="`bg-main-gradient flex flex-col fixed 
        shadow-black shadow-custom-main rounded-md top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 trigger-overlay-contact
        z-30 text-white max-h-[85vh] overflow-y-auto ${width}`">
    
            <MainContainerSlot :bgMainBtn="'bg-gradient-blue'" width='w-full' class=""
            :textBtn1="'Fermer'" :textBtn2="'Envoyer'" titleContainer="Support et aide" @toggleMenu="toggleMenu">
                <div class="text-xl 
                flex flex-col justify-center items-center w-full rounded-[3px] my-[40px]">

                <div class="flex flex-col w-full gap-16 items-center" >

                    <div class="w-1/2">
                        <p class="font-light">Nous sommes là pour vous aider ! <span class="block"></span>N'hésitez pas à nous contacter en précisant comment nous pouvons vous assister.</p>
                    </div>

                    <div class="flex gap-10 w-1/2">
                        <div class="w-1/2">
                            <label class="text-white font-light text-[20px]" for="input-firstname">Prénom</label>
                            <InputBase 
                                unicode="🔒"
                                id="input-firstname" 
                                v-model="input.firstName" 
                                extraClass="" 
                                :placeholder="`test`"
                                type="text"
                            />
                        </div>
                        <div class="w-1/2">
                            <label class="text-white font-light text-[20px]" for="input-lastname">Nom</label>
                            <InputBase 
                                unicode="🔒"
                                id="input-lastname" 
                                v-model="input.lastName" 
                                extraClass="" 
                                :placeholder="`test`"
                                type="text"
                            />
                        </div>
                    </div>

                    <div class="flex flex-col w-1/2">
                        <label class="text-white font-light text-[20px]" for="input-email">Email</label>
                        <InputBase 
                            unicode="🔒"
                            id="input-email" 
                            v-model="input.email" 
                            extraClass="" 
                            :placeholder="`test`"
                            type="email"
                        />
                    </div>

                    <div class="flex flex-col w-1/2">
                        <label class="text-white font-light text-[20px]" for="content-email">Message</label>
                        <TextAreaBase 
                            id="content-email" 
                            v-model="input.contentEmail" 
                            :placeholder="`...`"
                        />
                    </div>
                </div>
           
                
                </div>
            </MainContainerSlot>

        </div>
    </div>
</template>

    

<script setup>
    import { reactive } from 'vue';
    import MainContainerSlot from '@/component/containerSlot/MainContainerSlot.vue';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';
    import InputBase from '@/component//input/InputBase.vue';
    import TextAreaBase from '@/component//input/TextAreaBase.vue';
    import { sendEmailToSupport } from '@/composable/useBackendActionData';
    

    // props, variables..
    const props = defineProps({
        width: {default: ''},
        infoTransaction: {default: []},
    });

    const input = reactive({
        firstName: '',
        lastName: '',
        email: '',
        contentEmail: ''
    });


    const isOverlayActive = defineModel();

    useEscapeKey(isOverlayActive, () => {
        isOverlayActive.value = false;
    });

    useClickOutside( '.trigger-overlay-contact', isOverlayActive, () => {
        isOverlayActive.value = false;
    },);

    async function toggleMenu(request) {
        switch(request) {
            case 'valid': {
                if(!isOverlayActive.value) return;
                const response = await sendEmailToSupport({
                    firstName: input.firstName,
                    lastName: input.lastName,
                    emailSender: input.email,
                    message: input.contentEmail
                });
                console.log('EmailMess', response);
                if(response.isSuccessRequest) resetInputs();
                break;
            }
            case 'cancel': {
                closeOverlay();
                break;
            }
        }
    }

    function closeOverlay() {
        isOverlayActive.value = false;
    }

    function resetInputs() {
        input.firstName = '';
        input.lastName = '';
        input.email = '';
        input.contentEmail = '';
    }

</script>