<template>
    <section class="relative rounded-[3px] w-full text-white overflow-hidden bg-main-gradient  
        shadow-black shadow-custom-main mt-[20px]">

        <div 
            @click="toggleParamsSearch"    
            class="absolute w-full mt-5 shadow-black shadow-custom-main bg-gradient-x-blue py-2 pl-3
                font-light flex justify-start gap-2 text-[18px] text-white 
                hover:shadow-slate-500 cursor-pointer">
            
            <UseIconLoader :svg=iconConfig :nameIcon="typeIconShowParams"  />
            <h1>Gestion de mot de passe</h1>
        </div>
        
        <div :class="`w-full gradient-border overflow-hidden ${paddingForMenuOpen}`">
            <form class="mt-16" @submit.prevent="handleSubmit()">
                <TransitionAxeY>
                    <div v-show="toggleShowParams">

                        <div class="xl:flex w-full">

                            <div class="xl:w-[40%] 2xl:w-[45%] flex justify-center">
                                <ImageEditPass class="pt-5 w-full" :svg="imageConfig" />
                            </div>

                            <div class="grow flex flex-col justify-center">
    
                                <!-- Errors -->
                                <div class="relative">
                                    <p class="text-sm font-light absolute text-red-300">{{ textError }}</p>
                                </div>
                                
                                <div class="xl:mt-5 w-full flex">
                                    <div class="w-full flex flex-col justify-center xl:justify-center 2xl:justify-evenly 
                                            items-center mt-3 gap-2 sm:gap-5 2xl:gap-0 sm:flex-row"> 

                                        <div class="flex flex-col w-1/3 min-w-[280px] lg:min-w-[300px] 2xl:min-w-[300px] md:w-1/4">
                                            <label class="pl-2 text-white font-light" for="input-old-pass">Mot de passe actuel</label>
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
                                        <div class="hidden sm:flex flex-col w-1/3 min-w-[280px] lg:min-w-[300px] 2xl:min-w-[300px] md:w-1/4"></div>
                                    </div>
                                </div>

                                <div class="mt-3 w-full flex">

                                    <div class="w-full flex flex-col justify-center xl:justify-center 2xl:justify-evenly 
                                            items-center mt-3 gap-5 2xl:gap-0 sm:flex-row">
                                        <div class="flex flex-col w-1/3 min-w-[280px] lg:min-w-[300px] 2xl:min-w-[300px] md:w-1/4">
                                            <label class="pl-2 text-white font-light" for="input-confirm-pass">Nouveau mot de passe</label>
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
                                        <div class="flex flex-col w-1/3 min-w-[280px] lg:min-w-[300px] 2xl:min-w-[300px] md:w-1/4">
                                            <label class="pl-2 text-white font-light" for="input-confirm-newpass">Confirmation du mot de passe</label>
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
    import ImageEditPass from '@/component//svgs/ImageEditPass.vue';
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
    const imageConfig = setSvgConfig({width:'300px', fill:'white' });

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