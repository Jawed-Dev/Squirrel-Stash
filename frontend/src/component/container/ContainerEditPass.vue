<template>
    <section class="relative rounded-[3px] w-full text-white overflow-hidden bg-main-gradient  
        shadow-black shadow-custom-main mt-[20px]">
        <h2 class="absolute w-full mt-5 shadow-black shadow-custom-main bg-gradient-x-blue p-2 
        font-light flex justify-start text-[18px] text-white">Édition de mot de passe</h2>
        
        <div class="gradient-border overflow-hidden">
            <form class="flex flex-col mt-24" @submit.prevent="handleSubmit()">

                <!-- Errors -->
                <div class="relative pl-5 pb-5">
                    <p class="text-sm font-light absolute text-red-300">{{ textError }}</p>
                </div>
                <div class="flex justify-start w-full gap-24 pl-52"> 
                    <div class="flex flex-col justify-center w-1/4">
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
                </div>
                <div class="mt-12 flex justify-start w-full gap-24 pl-52">
                    <div class="flex flex-col w-1/4 ">
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
                    <div class="flex flex-col w-1/4">
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
                    <!-- <div class="pl-10 flex flex-col justify-between w-1/2">
                        <label class="text-white font-light text-[16px]" for="input-pass">Force du mot de passe</label>
                        <div v-if="inputsPass.newPass.length > 0" class="flex flex-col w-full ">
                            <p class="text font-light">{{textLevelPass}}</p>
                            <div id="strength-level-pass" class="flex items-center gap-1 w-full ">
                                <p v-if="strengthLevelPass > 0" v-for="i in strengthLevelPass" :key="i" :class="`flex items-end border-b-2 ${colorBorderLevelPass} w-[33%]`"></p>
                                <p v-else class="flex items-end border-b-2 border-red-600 w-1/3"></p>
                            </div>
                        </div>
                    </div> -->
                </div>

                <div class="w-full flex items-center justify-center mt-8 my-3">
                    <div class="flex justify-center shadow-black shadow-custom-main w-1/5">
                        <button class=
                            "text-[17px] w-full rounded-sm py-2 bg-gradient-blue 
                            rounded-br-[3px] font-light">Éditer
                        </button>
                    </div>
                </div>
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

    // life cycle, functions
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
    
</script>