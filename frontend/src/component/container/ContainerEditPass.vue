<template>
    <section class="relative rounded-[3px] w-full text-white overflow-hidden bg-main-gradient  
        shadow-black shadow-custom-main mt-[20px]">

        <div 
            @click="toggleParamsSearch"    
            class="absolute w-full mt-5 shadow-black shadow-custom-main bg-gradient-x-blue py-2 pl-3
                font-light flex justify-start gap-2 text-[18px] text-white 
                hover:shadow-main-blue cursor-pointer">
            <h1>Gestion de mot de passe</h1>
            <UseIconLoader :svg=iconConfig :nameIcon="typeIconShowParams"  />
        </div>
        
        <div :class="`gradient-border overflow-hidden ${paddingForMenuOpen}`">
            <form class="mt-16" @submit.prevent="handleSubmit()">
                <TransitionAxeY>
                    <div v-show="toggleShowParams">
                        <!-- Errors -->
                        <div class="relative pl-5 pb-5">
                            <p class="text-sm font-light absolute text-red-300">{{ textError }}</p>
                        </div>
        
                        <div class="flex flex-col justify-center items-center gap-3 mt-3
                            md:gap-20 lg:gap-32 xl:gap-48 md:flex-row"> 
                            <div class="flex flex-col w-1/2 min-w-[250px] lg:min-w-[350px] md:w-1/4">
                                <label class="text-white font-light" for="input-old-pass">Mot de passe actuel</label>
                                <InputBase 
                                    iconName="Password"
                                    id="input-old-pass" 
                                    v-model="inputsPass.oldPass" 
                                    v-model:stateError="errorInputs.oldPass"
                                    placeholder="Non défini"
                                    type="password"
                                    validFormat="password"
                                    :hideAnimation="true"
                                />
                            </div>
                            <div class="hidden md:flex flex-col w-1/2 min-w-[250px] lg:min-w-[350px] md:w-1/4"></div>
                        </div>
                        <div class="flex flex-col justify-center items-center gap-3 mt-3
                            md:gap-20 lg:gap-32 xl:gap-48 md:flex-row">
                            <div class="flex flex-col w-1/2 min-w-[250px] lg:min-w-[350px] md:w-1/4">
                                <label class="text-white font-light" for="input-confirm-pass">Nouveau mot de passe</label>
                                <InputBase 
                                    iconName="Password"
                                    id="input-confirm-pass" 
                                    v-model="inputsPass.confirmNewPass" 
                                    v-model:stateError="errorInputs.confirmNewPass"
                                    placeholder="Non défini"
                                    type="password"
                                    validFormat="password"
                                    :hideAnimation="true"
                                />
                            </div>
                            <div class="flex flex-col w-1/2 min-w-[250px] lg:min-w-[350px] md:w-1/4">
                                <label class="text-white font-light" for="input-confirm-newpass">Confirmation du mot de passe</label>
                                <InputBase 
                                    iconName="Password"
                                    id="input-confirm-newpass" 
                                    v-model="inputsPass.newPass" 
                                    v-model:stateError="errorInputs.newPass"
                                    placeholder="Non défini"
                                    type="password"
                                    validFormat="password"
                                    :hideAnimation="true"
                                />
                            </div>
                        </div>
        
                        <div class="w-full flex justify-center mt-8 my-3">
                            <div class="shadow-black shadow-custom-main min-w-[200px] w-1/4 md:w-1/5 overflow-x-hidden text-ellipsis">
                                <button class="w-full rounded-sm py-2 bg-gradient-blue rounded-br-[3px] font-light">Éditer
                                </button>
                            </div>
                        </div>
                    </div>
                </TransitionAxeY>
            </form>
        </div>
    </section>
    <TransitionPopUp duration-in="500" duration-out="500">
        <OverlaySuccessAction text="Votre mot de passe a été modifié." v-if="isSuccessAction" v-model:overlayActive="isSuccessAction" />
    </TransitionPopUp>
</template>


<script setup>
    import { ref, reactive, computed, defineAsyncComponent } from 'vue';
    import InputBase from '@/component/input/InputBase.vue';
    import { updatePasswordByUserId } from '@/composable/useBackendActionData';
    import { isAnyMandatInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';
    import TransitionPopUp from '@/component/transition/TransitionPopUp.vue';
    import TransitionAxeY from '@/component/transition/TransitionAxeY.vue';
    import UseIconLoader from '@/composable/useIconLoader.vue';
    import { setSvgConfig } from '@/svg/svgConfig';
    const OverlaySuccessAction = defineAsyncComponent(() => import('@/component/overlay/OverlaySuccessAction.vue'));

    const inputsPass = reactive({
        oldPass: '',
        newPass: '',
        confirmNewPass: ''
    });

    const errorInputs = reactive({
        oldPass: false,
        newPass: false,
        confirmNewPass: false,
    });

    const submitError = ref(null);
    const isSuccessAction = ref(false);
    const toggleShowParams= ref(false);
    const iconConfig = setSvgConfig({width:'30px', fill:'white' });

    // life cycle, functions

    const typeIconShowParams = computed(() => {
        return (toggleShowParams.value) ? 'ArrowUp' : 'ArrowDown';
    });

    const paddingForMenuOpen = computed(() => {
        return (toggleShowParams.value) ? '' : 'pb-5';
    });

    const textError = computed(() => {
        if(submitError.value === TYPE_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS) return TEXT_SUBMIT_ERROR.ALL_INPUTS_MANDATORY;
        else if(submitError.value === TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST) return "La requête a échoué.";
        else if(submitError.value === TYPE_SUBMIT_ERROR.CONFIRM_PASS_ERROR) return TEXT_SUBMIT_ERROR.CONFIRM_PASS_ERROR;
    });

    const strengthLevelPass = computed(() => {
        let levelPass = 0;
        if(inputsPass.newPass.length >= 4) levelPass++;
        if (/[A-Z]/.test(inputsPass.newPass) && /\d/.test(inputsPass.newPass)) levelPass++;
        if (/[^A-Za-z0-9]/.test(inputsPass.newPass)) levelPass++;
        return levelPass;
    });

    const colorBorderLevelPass = computed(() => {
        if(strengthLevelPass.value === 0) return "border-white";
        else if(strengthLevelPass.value === 1) return "border-red-600";
        else if(strengthLevelPass.value === 2) return "border-orange-400";
        else if(strengthLevelPass.value === 3) return "border-green-600";
    });

    const textLevelPass = computed(() => {
        if(strengthLevelPass.value === 0) return "Mauvais";
        else if(strengthLevelPass.value === 1) return "Mauvais";
        else if(strengthLevelPass.value === 2) return "Bon";
        else if(strengthLevelPass.value === 3) return "Excellent";
    });


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
        else if(isPasswordsAreDifferent()) {
            submitError.value = TYPE_SUBMIT_ERROR.CONFIRM_PASS_ERROR;
            resetInputs();
            return;
        }
        const response = await updatePasswordByUserId({ oldPass: inputsPass.oldPass, newPass: inputsPass.newPass });
        const isSuccessRequest = response?.isSuccessRequest;
        if(!isSuccessRequest) {
            submitError.value = TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST;
            return;
        }
        clearInputsPass();
        submitError.value = null;
        isSuccessAction.value = true;
    }

    function clearInputsPass() {
        inputsPass.oldPass = '';
        inputsPass.newPass = '';
        inputsPass.confirmNewPass = '';
    }

    function getStatesErrorInputs() {
        return {
            oldpass: errorInputs.oldPass,
            confirmNewPass: errorInputs.confirmNewPass,
            newPass: errorInputs.newPass
        }
    }
    
    function getValuesMandantInputs() {
        return {
            oldpass: inputsPass.oldPass,
            confirmNewPass: inputsPass.confirmNewPass,
            newPass: inputsPass.newPass
        }
    }

    function isPasswordsAreDifferent() {
        if(inputsPass.newPass.length <= 0 || inputsPass.confirmNewPass.length <= 0) return false;
        return inputsPass.newPass !== inputsPass.confirmNewPass;
    }

    function toggleParamsSearch() {
        toggleShowParams.value = !toggleShowParams.value;
    }
    
</script>