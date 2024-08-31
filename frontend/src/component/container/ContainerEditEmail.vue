<template>
    <section class="relative rounded-[3px] w-full text-white overflow-hidden bg-main-gradient  
     shadow-main mt-[20px]">
        <div 
            @click="toggleParamsSearch"    
            class="absolute w-full mt-3 shadow-main bg-gradient-x-blue py-2 pl-3
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
                                <ImageEditEmail class="pt-5 w-full" :svg="sizeImage" />
                            </div>

                            <div class="mt-2 grow flex flex-col justify-center">                
                                <div class="xl:mt-5 w-full flex">
                                    <div class="w-full flex flex-col justify-center xl:justify-center 2xl:justify-evenly 
                                            items-center gap-2 sm:gap-12 2xl:gap-0 sm:flex-row">
                                        <div class="flex flex-col w-1/3 min-w-[270px] lg:min-w-[280px] 2xl:min-w-[300px] md:w-1/4">
                                            <label class="pl-2 text-white font-light" for="input-current-mail">Email</label>
                                            <ContainerTextUnderline 
                                                iconName="Email"
                                                :text="inputsMail.currentMail" 
                                                extraClass="text-[16px]"
                                                id="input-current-mail"
                                            />
                                        </div>
                                        <div class="mt-2 sm:mt-0 flex flex-col w-1/3 min-w-[270px] lg:min-w-[280px] 2xl:min-w-[300px] md:w-1/4">
                                            <label class="pl-2 text-white font-light" for="input-new-mail">Nouvel email</label>
                                            <InputBase 
                                                iconName="Email"
                                                id="input-new-mail" 
                                                v-model="inputsMail.newMail" 
                                                v-model:stateError="errorInput"
                                                v-model:mandatoryInput="mandatoryInput"
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
                        
                        <div class="w-full flex justify-center mt-12 my-3">
                            <div class=" shadow-main min-w-[250px] w-1/4 md:w-1/5 overflow-x-hidden text-ellipsis">
                                <button class="w-full rounded-sm py-2 bg-gradient-blue rounded-br-[3px] font-light hover:opacity-90">Editer</button>
                            </div>
                        </div>
                    </div>
                </TransitionAxeY>
            </form>
        </div>
    </section>
</template>



<script setup>
    import { ref, computed, reactive, onMounted, defineAsyncComponent } from 'vue';
    import { storeEmailUser } from '@/storePinia/useStoreDashboard';
    import { updateStoreUserEmail } from '@/storePinia/useUpdateStoreByBackend';
    import { sendUpdateMail } from '@/composable/useBackendActionData';
    import InputBase from '@/component/input/InputBase.vue';
    import ContainerTextUnderline from '@/component/container/ContainerTextUnderline.vue'; 
    import { isAnyMandatoryInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';
    import TransitionAxeY from '@/component/transition/TransitionAxeY.vue';
    import UseIconLoader from '@/composable/useIconLoader.vue';
    import { setSvgConfig } from '@/svg/svgConfig';
    import ImageEditEmail from '@/component//svgs/ImageEditEmail.vue';
    import { createToast } from '@/composable/useToastNotification';
    import { getScreenSize } from '@/composable/useSizeScreen';
    
    
    const emailUser = storeEmailUser();
    const inputsMail = reactive({
        currentMail: '',
        newMail: '',
    });
    const errorInput = ref(false);
    const mandatoryInput = ref(false); 
    const toggleShowParams= ref(false);
    const iconConfig = setSvgConfig({width:'30px', fill:'white' });
    const imageConfigBig = setSvgConfig({width:'300px', fill:'white' });
    const imageConfig = setSvgConfig({width:'240px', fill:'white' });
    const { widthScreenValue } = getScreenSize();

    // life cycle, functions
    onMounted(async () => {
        await updateStoreUserEmail();
        updateEditEmail();
    });

    const sizeImage = computed(() => {
        return widthScreenValue.value < 1024 ? imageConfig : imageConfigBig;
    });

    const typeIconShowParams = computed(() => {
        return (toggleShowParams.value) ? 'ArrowUp' : 'ArrowDown';
    });

    const paddingForMenuOpen = computed(() => {
        return (toggleShowParams.value) ? '' : 'pb-1';
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
        if(isAnyMandatoryInputEmpty(allMandatoryValInputs)) {
            activeErrorMandatInput();
            createToast(TEXT_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS, 'error');
            return;
        }
        else if(isAnyInputError(allErrorsInputs)) {
            return;
        }
        else if(isNewEmailSameAsOld()) {
            resetInputs();
            return;
        }
        const response = await sendUpdateMail({
            newEmail: inputsMail.newMail
        });
        const isSuccessRequest = response?.isSuccessRequest;
        if(!isSuccessRequest) {
            return;
        }
        await updateStoreUserEmail();
        updateEditEmail();
        clearInputsEmail();
        createToast('Un email a été envoyé sur votre adresse actuelle,\n pour être modifiée.', 'success');
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
    function activeErrorMandatInput() {
        if (!inputsMail.newMail) mandatoryInput.value = true;
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