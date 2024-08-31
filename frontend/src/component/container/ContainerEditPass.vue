<template>
    <section class="relative rounded-[3px] w-full text-white overflow-hidden bg-main-gradient  
         shadow-main mt-[20px]">

        <div 
            @click="toggleParamsSearch"    
            class="absolute w-full mt-3 shadow-main bg-gradient-x-blue py-2 pl-3
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
                                <ImageEditPass class="pt-5 w-full" :svg="sizeImage" />
                            </div>

                            <div class="grow flex flex-col justify-center">                            
                                <div class="xl:mt-5 w-full flex">
                                    <div class="w-full flex flex-col justify-center xl:justify-center 2xl:justify-evenly 
                                            items-center gap-2 sm:gap-12 2xl:gap-0 sm:flex-row"> 

                                        <div class="flex flex-col w-1/3 min-w-[270px] lg:min-w-[280px] 2xl:min-w-[300px] md:w-1/4">
                                            <label class="pl-2 text-white font-light" for="input-old-pass">Mot de passe actuel</label>
                                            <InputBase 
                                                iconName="Password"
                                                id="input-old-pass" 
                                                v-model="inputsPass.oldPass" 
                                                v-model:stateError="errorInputs.oldPass"
                                                v-model:mandatoryInput="mandatoryInputs.oldPass"
                                                placeholder="Non défini"
                                                type="password"
                                                validFormat="password"
                                                :hideAnimation="true"
                                            />
                                        </div>
                                        <div class="hidden sm:flex flex-col w-1/3 min-w-[270px] lg:min-w-[280px] 2xl:min-w-[300px] md:w-1/4"></div>
                                    </div>
                                </div>

                                <div class="mt-5 w-full flex">

                                    <div class="w-full flex flex-col justify-center xl:justify-center 2xl:justify-evenly 
                                            items-center gap-2 sm:gap-12 2xl:gap-0 sm:flex-row">
                                        <div class="flex flex-col w-1/3 min-w-[270px] lg:min-w-[280px] 2xl:min-w-[300px] md:w-1/4">
                                            <label class="pl-2 text-white font-light" for="input-confirm-pass">Nouveau mot de passe</label>
                                            <InputBase 
                                                iconName="Password"
                                                id="input-confirm-pass" 
                                                v-model="inputsPass.confirmNewPass" 
                                                v-model:stateError="errorInputs.confirmNewPass"
                                                v-model:mandatoryInput="mandatoryInputs.confirmNewPass"
                                                placeholder="Non défini"
                                                type="password"
                                                validFormat="password"
                                                :hideAnimation="true"
                                            />
                                        </div>
                                        <div class="mt-5 sm:mt-0 flex flex-col w-1/3 min-w-[270px] lg:min-w-[280px] 2xl:min-w-[300px] md:w-1/4">
                                            <label class="pl-2 text-white font-light" for="input-confirm-newpass">Confirmation du mot de passe</label>
                                            <InputBase 
                                                iconName="Password"
                                                id="input-confirm-newpass" 
                                                v-model="inputsPass.newPass" 
                                                v-model:stateError="errorInputs.newPass"
                                                v-model:mandatoryInput="mandatoryInputs.newPass"
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
    import { ref, reactive, computed } from 'vue';
    import InputBase from '@/component/input/InputBase.vue';
    import { updatePasswordByUserId } from '@/composable/useBackendActionData';
    import { isAnyMandatoryInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';
    import TransitionAxeY from '@/component/transition/TransitionAxeY.vue';
    import UseIconLoader from '@/composable/useIconLoader.vue';
    import ImageEditPass from '@/component//svgs/ImageEditPass.vue';
    import { setSvgConfig } from '@/svg/svgConfig';
    import { createToast } from '@/composable/useToastNotification';
    import { getScreenSize } from '@/composable/useSizeScreen';

    

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

    const mandatoryInputs = reactive({
        oldPass: false,
        newPass: false,
        confirmNewPass: false,
    });

    const toggleShowParams= ref(false);
    const iconConfig = setSvgConfig({width:'30px', fill:'white' });
    const imageConfigBig = setSvgConfig({width:'300px', fill:'white' });
    const imageConfig = setSvgConfig({width:'240px', fill:'white' });
    const { widthScreenValue } = getScreenSize();

    // life cycle, functions
    const sizeImage = computed(() => {
        return widthScreenValue.value < 1024 ? imageConfig : imageConfigBig;
    });

    const typeIconShowParams = computed(() => {
        return (toggleShowParams.value) ? 'ArrowUp' : 'ArrowDown';
    });

    const paddingForMenuOpen = computed(() => {
        return (toggleShowParams.value) ? '' : 'pb-1';
    });

    async function handleSubmit() {
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
        else if(isPasswordsAreDifferent()) {
            createToast(TEXT_SUBMIT_ERROR.CONFIRM_PASS_ERROR, 'error');
            clearInputsPass();
            return;
        }
        const response = await updatePasswordByUserId({ oldPass: inputsPass.oldPass, newPass: inputsPass.newPass });
        const isSuccessRequest = response?.isSuccessRequest;
        if(!isSuccessRequest) {
            return;
        }
        clearInputsPass();
        createToast('Votre mot de passe a été édité.', 'success');
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

    function activeErrorMandatInputs() {
        if (!inputsPass.oldPass) mandatoryInputs.oldPass = true;
        if (!inputsPass.newPass) mandatoryInputs.newPass = true;
        if (!inputsPass.confirmNewPass) mandatoryInputs.confirmNewPass = true;
    }

    function isPasswordsAreDifferent() {
        if(inputsPass.newPass.length <= 0 || inputsPass.confirmNewPass.length <= 0) return false;
        return inputsPass.newPass !== inputsPass.confirmNewPass;
    }

    function toggleParamsSearch() {
        toggleShowParams.value = !toggleShowParams.value;
    }
    
</script>