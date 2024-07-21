<template>
    <div class="fixed inset-0 bg-black bg-opacity-80 z-30">
        <div :class="`bg-main-gradient flex flex-col fixed 
        shadow-black shadow-custom-main rounded-md top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 trigger-overlay-contact
        z-30 text-white max-h-[85vh] overflow-y-auto ${width}`">
    
            <MainContainerSlot :bgMainBtn="'bg-gradient-blue'" width='w-full'
            :textBtn1="'Fermer'" :textBtn2="'Envoyer'" titleContainer="Support et Aide" @toggleMenu="toggleMenu">
                
                <!-- Errors -->
                <div class="relative pl-2">
                    <p class="font-light absolute text-red-300">{{ textError }}</p>
                </div>
                <div class="flex flex-col justify-center items-center w-full rounded-[3px] my-[40px]">
                    <div class="flex flex-col w-full items-center px-10">
                        <div class="flex flex-col gap-16">
                            <div class="w-full px-5">
                                <p class="font-light text-xl">Nous sommes là pour vous aider ! 
                                <span class="block"></span>N'hésitez pas à nous contacter en précisant comment nous pouvons vous assister.</p>
                            </div>

                            <div class="flex gap-10 px-5">
                                <div class="w-1/2">
                                    <label class="text-white font-light" for="input-firstname">Prénom</label>
                                    <InputBase 
                                        iconName = 'Name'
                                        id="input-firstname" 
                                        v-model="input.firstName" 
                                        v-model:stateError="errorInputs.firstName"
                                        :placeholder="`Non défini`"
                                        type="text"
                                        validFormat="firstName"
                                        :hideAnimation="true"
                                    />
                                </div>
                                <div class="w-1/2">
                                    <label class="text-white font-light" for="input-lastname">Nom</label>
                                    <InputBase 
                                        iconName = 'Name'
                                        id="input-lastname" 
                                        v-model="input.lastName" 
                                        v-model:stateError="errorInputs.lastName"
                                        :placeholder="`Non défini`"
                                        type="text"
                                        validFormat="lastName"
                                        :hideAnimation="true"
                                    />
                                </div>
                            </div>
    
                            <div class="flex flex-col w-1/2 px-5">
                                <label class="text-white font-light" for="input-email">Email</label>
                                <InputBase 
                                    iconName = 'Email'
                                    id="input-email" 
                                    v-model="input.email" 
                                    v-model:stateError="errorInputs.email"
                                    :placeholder="`Non défini`"
                                    type="email"
                                    validFormat="email"
                                    :hideAnimation="true"
                                />
                            </div>
    
                            <div class="flex flex-col px-5">
                                <label class="text-white font-light" for="content-email">Message</label>
                                <TextAreaBase 
                                    id="content-email" 
                                    v-model="input.contentEmail" 
                                    v-model:stateError="errorInputs.contentEmail"
                                    :placeholder="`...`"
                                    validFormat="message"
                                    :hideAnimation="true"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </MainContainerSlot>
        </div>
    </div>
    <TransitionPopUp duration-in="500" duration-out="500">
        <OverlaySuccessAction text="Votre mot de passe a été modifié." v-if="isSuccessAction" v-model:overlayActive="isSuccessAction" />
    </TransitionPopUp>
</template>

    

<script setup>
    import { reactive, ref, computed, watch } from 'vue';
    import MainContainerSlot from '@/component/containerSlot/MainContainerSlot.vue';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';
    import InputBase from '@/component//input/InputBase.vue';
    import TextAreaBase from '@/component//input/TextAreaBase.vue';
    import { sendEmailToSupport } from '@/composable/useBackendActionData';
    import { isAnyMandatInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';
    import TransitionPopUp from '@/component/transition/TransitionPopUp.vue';
    const OverlaySuccessAction = defineAsyncComponent(() => import('@/component/overlay/OverlaySuccessAction.vue'));

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

    const submitError = ref(null);
    const isSuccessAction = ref(false);
    const isOverlayActive = defineModel();

    // life cycle, functions
    const textError = computed(() => {
        if(submitError.value === TYPE_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS) return TEXT_SUBMIT_ERROR.ALL_INPUTS_MANDATORY;
        else if(submitError.value === TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST) return "La requête a échoué.";
        else if(submitError.value === TYPE_SUBMIT_ERROR.CONFIRM_PASS_ERROR) return TEXT_SUBMIT_ERROR.CONFIRM_PASS_ERROR;
    });

    watch(isOverlayActive, () => {
        submitError.value = null;
    });

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
                if(isAnyMandatInputEmpty(allMandatoryValInputs)) {
                    submitError.value = TYPE_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS;
                    return;
                }
                else if(isAnyInputError(allErrorsInputs)) {
                    submitError.value = TYPE_SUBMIT_ERROR.INPUTS_FORMAT_ERRORS;
                    return;
                }
                const response = await sendEmailToSupport({
                    firstName: input.firstName,
                    lastName: input.lastName,
                    emailSender: input.email,
                    message: input.contentEmail
                });
                const isSuccessRequest = response?.isSuccessRequest;
                if(!isSuccessRequest) {
                    submitError.value = TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST;
                    return;
                }
                resetInputs();
                submitError.value = null;
                isSuccessAction.value = true;
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