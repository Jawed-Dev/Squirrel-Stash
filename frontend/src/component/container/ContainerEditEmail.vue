<template>
    <section class="relative rounded-[3px] w-full text-white overflow-hidden bg-main-gradient  
    shadow-black shadow-custom-main mt-[20px]">
        <div 
            @click="toggleParamsSearch"    
            class="absolute w-full mt-5 shadow-black shadow-custom-main bg-gradient-x-blue py-2 pl-3
                font-light flex justify-start gap-2 text-[18px] text-white 
                hover:shadow-slate-500 cursor-pointer">
            <UseIconLoader :svg=iconConfig :nameIcon="typeIconShowParams"  />
            <h1>Gestion d'email</h1>
        </div>

        <div :class="`w-full gradient-border overflow-hidden ${paddingForMenuOpen}`">
            <form class="mt-16" @submit.prevent="handleSubmit()">
                <TransitionAxeY>
                    <div v-show="toggleShowParams">

                        <div class="xl:flex w-full">

                            <div class="xl:w-[40%] 2xl:w-[45%] flex justify-center">
                                <ImageEditEmail class="pt-5 w-full" :svg="imageConfig" />
                            </div>

                            <div class="grow flex flex-col justify-center">

                                <!-- Errors -->
                                <div class="relative">
                                    <p class="text-sm font-light absolute text-red-300">{{ textError }}</p>
                                </div>
                
                                <div class="xl:mt-5 w-full flex">
                                    <div class="w-full flex flex-col justify-center xl:justify-center 2xl:justify-evenly 
                                            items-center mt-3 gap-5 2xl:gap-0 sm:flex-row">
                                        <div class="flex flex-col w-1/3 min-w-[280px] lg:min-w-[300px] 2xl:min-w-[300px] md:w-1/4">
                                            <label class="pl-2 text-white font-light" for="input-current-mail">Email</label>
                                            <ContainerTextUnderline 
                                                iconName="Email"
                                                :text="inputsMail.currentMail" 
                                                extraClass="text-[16px]"
                                                id="input-current-mail"
                                            />
                                        </div>
                                        <div class="flex flex-col w-1/3 min-w-[280px] lg:min-w-[300px] 2xl:min-w-[300px] md:w-1/4">
                                            <label class="pl-2 text-white font-light" for="input-new-mail">Nouvel email</label>
                                            <InputBase 
                                                iconName="Email"
                                                id="input-new-mail" 
                                                v-model="inputsMail.newMail" 
                                                v-model:stateError="errorInput"
                                                extraClass="" 
                                                placeholder="Non défini"
                                                type="mail"
                                                validFormat="email"
                                                :hideAnimation="true"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="w-full flex justify-center sm:mt-12 my-3">
                            <div class="shadow-black shadow-custom-main min-w-[250px] w-1/4 md:w-1/5 overflow-x-hidden text-ellipsis">
                                <button class="w-full rounded-sm py-2 bg-gradient-blue rounded-br-[3px] font-light">Editer</button>
                            </div>
                        </div>
                    </div>
                </TransitionAxeY>
            </form>
        </div>
    </section>
    <TransitionPopUp duration-in="500" duration-out="500">
        <OverlaySuccessAction text="Un email vous a été envoyé pour confirmer votre nouvelle adresse email." v-if="isSuccessAction" v-model:overlayActive="isSuccessAction" />
    </TransitionPopUp>
</template>



<script setup>
    import { ref, computed, reactive, onMounted, defineAsyncComponent } from 'vue';
    import { storeEmailUser } from '@/storePinia/useStoreDashboard';
    import { updateStoreUserEmail } from '@/storePinia/useUpdateStoreByBackend';
    import { sendUpdateMail } from '@/composable/useBackendActionData';
    import InputBase from '@/component/input/InputBase.vue';
    import ContainerTextUnderline from '@/component/container/ContainerTextUnderline.vue'; 
    import { isAnyMandatInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';
    import TransitionPopUp from '@/component/transition/TransitionPopUp.vue';
    import TransitionAxeY from '@/component/transition/TransitionAxeY.vue';
    import UseIconLoader from '@/composable/useIconLoader.vue';
    import { setSvgConfig } from '@/svg/svgConfig';
    import ImageEditEmail from '@/component//svgs/ImageEditEmail.vue';

    const OverlaySuccessAction = defineAsyncComponent(() => import('@/component/overlay/OverlaySuccessAction.vue'));
    
    const emailUser = storeEmailUser();
    const inputsMail = reactive({
        currentMail: '',
        newMail: '',
    });

    const errorInput = ref(false);
    const submitError = ref(null);
    const isSuccessAction = ref(false);
    const toggleShowParams= ref(false);
    const iconConfig = setSvgConfig({width:'30px', fill:'white' });
    const imageConfig = setSvgConfig({width:'300px', fill:'white' });

    // life cycle, functions
    const textError = computed(() => {
        if(submitError.value === TYPE_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS) return TEXT_SUBMIT_ERROR.ALL_INPUTS_MANDATORY;
        else if(submitError.value === TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST) return "La requête a échoué.";
        else if(submitError.value === TYPE_SUBMIT_ERROR.NEW_EMAIL_ERROR) return TEXT_SUBMIT_ERROR.NEW_EMAIL_ERROR;
    });

    onMounted(async () => {
        await updateStoreUserEmail();
        updateEditEmail();
    });

    const typeIconShowParams = computed(() => {
        return (toggleShowParams.value) ? 'ArrowUp' : 'ArrowDown';
    });

    const paddingForMenuOpen = computed(() => {
        return (toggleShowParams.value) ? '' : 'pb-5';
    });

    function updateEditEmail() {
        inputsMail.currentMail = emailUser.currentEmail;
    }
    function clearInputsEmail() {
        inputsMail.newMail = '';
    }
    async function handleSubmit() {
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
        else if(isNewEmailSameAsOld()) {
            submitError.value = TYPE_SUBMIT_ERROR.NEW_EMAIL_ERROR;
            resetInputs();
            return;
        }
        const response = await sendUpdateMail({
            newEmail: inputsMail.newMail
        });
        const isSuccessRequest = response?.isSuccessRequest;
        if(!isSuccessRequest) {
            submitError.value = TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST;
            return;
        }
        await updateStoreUserEmail();
        updateEditEmail();
        clearInputsEmail();
        submitError.value = null;
        isSuccessAction.value = true;
    }

    function getStatesErrorInputs() {
        return {
            email: errorInput.value
        }
    }
    function getValuesMandantInputs() {
        return {
            email: inputsMail.newMail
        }
    }

    function isNewEmailSameAsOld() {
        const newMail = inputsMail.newMail.trim();
        if(inputsMail.currentMail.length <= 0 || inputsMail.newMail.length <= 0) return false;
        return inputsMail.currentMail === newMail;
    }

    function toggleParamsSearch() {
        toggleShowParams.value = !toggleShowParams.value;
    }
</script>