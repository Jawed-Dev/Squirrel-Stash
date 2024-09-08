<template>
    <div class="fixed inset-0 bg-black bg-opacity-80 z-30">
        <div :class="`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-30 text-white rounded-[3px] 
                 shadow-main trigger-overlay-contact bg-main-gradient
                w-full md:w-[720px] font-main`">
    
            <ContainerSlotOverlay :bgMainBtn="'bg-gradient-blue'" width='w-full'
            :textBtn1="'Fermer'" :textBtn2="'Envoyer'" titleContainer="Support et Aide" @toggleMenu="toggleMenu">
                <div class="max-h-[76vh] overflow-y-auto">
                    <div class="flex flex-col justify-center items-center w-full rounded-[3px] my-[40px]">
                        <div class="flex flex-col w-[90%] sm:w-full items-center px-5 sm:px-10">
                            <div class="flex flex-col gap-7">

                                <div class="w-full flex flex-col items-center justify-center">
                                    <p class="w-[90%] font-light sm:text-lg">Nous sommes là pour vous aider !
                                    <span class="block"></span>N'hésitez pas à nous contacter en précisant comment nous pouvons vous assister.</p>
                                </div>
    
                                <div class="flex flex-col sm:flex-row gap-5 px-5">
                                    <div class="w-full sm:w-1/2">
                                        <label class="pl-2 text-white font-light" for="input-firstname">Prénom</label>
                                        <InputBase 
                                            iconName = 'Name'
                                            id="input-firstname" 
                                            v-model="input.firstName" 
                                            v-model:stateError="errorInputs.firstName"
                                            v-model:mandatoryInput="mandatoryInputs.firstName"
                                            :placeholder="`Non défini`"
                                            type="text"
                                            validFormat="firstName"
                                            :hideAnimation="true"
                                        />
                                    </div>
                                    <div class="w-full sm:w-1/2">
                                        <label class="pl-2 text-white font-light" for="input-lastname">Nom</label>
                                        <InputBase 
                                            iconName = 'Name'
                                            id="input-lastname" 
                                            v-model="input.lastName" 
                                            v-model:stateError="errorInputs.lastName"
                                            v-model:mandatoryInput="mandatoryInputs.lastName"
                                            :placeholder="`Non défini`"
                                            type="text"
                                            validFormat="lastName"
                                            :hideAnimation="true"
                                        />
                                    </div>
                                </div>
        
                                <div class="flex flex-col w-full sm:w-1/2 px-5">
                                    <label class="pl-2 text-white font-light" for="input-email">Email</label>
                                    <InputBase 
                                        iconName = 'Email'
                                        
                                        id="input-email" 
                                        v-model="input.email" 
                                        v-model:stateError="errorInputs.email"
                                        v-model:mandatoryInput="mandatoryInputs.email"
                                        :placeholder="`Non défini`"
                                        type="email"
                                        validFormat="email"
                                        :hideAnimation="true"
                                    />
                                </div>
        
                                <div class="flex flex-col px-5">
                                    <label class="pl-2 text-white font-light" for="content-email">Message</label>
                                    <TextAreaBase 
                                        id="content-email" 
                                        v-model="input.contentEmail" 
                                        v-model:stateError="errorInputs.contentEmail"
                                        v-model:mandatoryInput="mandatoryInputs.contentEmail"
                                        :placeholder="`...`"
                                        validFormat="message"
                                        :hideAnimation="true"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </ContainerSlotOverlay>
        </div>
    </div>
    
</template>

    

<script setup>
    import { reactive, ref, watch } from 'vue';
    import ContainerSlotOverlay from '@/components/containerSlot/ContainerSlotOverlay.vue';
    import useClickOutside from '@/composables/useClickOutSide';
    import useEscapeKey from '@/composables/useEscapeKey';
    import InputBase from '@/components//input/InputBase.vue';
    import TextAreaBase from '@/components//input/TextAreaBase.vue';
    import { sendEmailToSupport } from '@/requests/useBackendAction';
    import { isAnyMandatoryInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/errors/useHandleError';
    import { createToast } from '@/composables/useToastNotification';

    // props, variables..
    const props = defineProps({
        width: {default: ''}
    });

    const input = reactive({
        firstName: '',
        lastName: '',
        email: '',
        contentEmail: ''
    });

    const errorInputs = reactive({
        firstName: false,
        lastName: false,
        email: false,
        contentEmail: false,
    });

    const mandatoryInputs = reactive({
        firstName: false,
        lastName: false,
        email: false,
        contentEmail: false,
    });

    const isSendingMail = ref(false);

    const isOverlayActive = defineModel();

    // life cycle, functions
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
                const allErrorsInputs = getStatesErrorInputs();
                const allMandatoryValInputs = getValuesMandantInputs();
                if(isAnyMandatoryInputEmpty(allMandatoryValInputs)) {
                    activeErrorMandatInputs();
                    createToast(TEXT_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS, 'error');
                    return;
                }
                else if(isAnyInputError(allErrorsInputs)) {
                    return;
                }
                if(isSendingMail.value) return;
                isSendingMail.value = true;
                const response = await sendEmailToSupport({
                    firstName: input.firstName,
                    lastName: input.lastName,
                    emailSender: input.email,
                    message: input.contentEmail
                });
                isSendingMail.value = false;
                const isSuccessRequest = response?.isSuccessRequest;
                if(!isSuccessRequest) {
                    return;
                }
                createToast('Votre demande de support a bien été envoyée.', 'success');
                closeOverlay();
                // resetInputs();
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
        disableErrorMandatInputs();
    }

    function resetInputs() {
        input.firstName = '';
        input.lastName = '';
        input.email = '';
        input.contentEmail = '';
    }

    function activeErrorMandatInputs() {
        if (!input.firstName) mandatoryInputs.firstName = true;
        if (!input.lastName) mandatoryInputs.lastName = true;
        if (!input.email) mandatoryInputs.email = true;
        if (!input.email) mandatoryInputs.contentEmail = true;
    }

    function disableErrorMandatInputs() {
        mandatoryInputs.firstName = false;
        mandatoryInputs.lastName = false;
        mandatoryInputs.email = false;
        mandatoryInputs.contentEmail = false;
    }

    function getStatesErrorInputs() {
        return {
            firstName: errorInputs.firstName,
            lastName: errorInputs.lastName,
            email: errorInputs.email,
            contentEmail: errorInputs.contentEmail
        }
    }

    function getValuesMandantInputs() {
        return {
            firstName: input.firstName,
            lastName: input.lastName,
            email: input.email,
            contentEmail: input.contentEmail
        }
    }

</script>