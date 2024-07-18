<template>
    <section class="relative rounded-[3px] w-full text-white overflow-hidden bg-main-gradient  
    shadow-black shadow-custom-main mt-[20px]">
    <h2 class="absolute w-full mt-5 shadow-black shadow-custom-main bg-gradient-x-blue p-2 
    font-light flex justify-start text-[18px] text-white">Édition d'email</h2>
    
        <div class="gradient-border overflow-hidden">
            <form class="mt-24 flex flex-col" @submit.prevent="handleSubmit()">
                <!-- Errors -->
                <div class="relative pl-5 pb-5">
                    <p class="text-sm font-light absolute text-red-300">{{ textError }}</p>
                </div>

                <div class="flex justify-start w-full gap-24 pl-52">
                    <div class="flex flex-col justify-center w-1/4">
                        <label class="text-white font-light" for="input-current-mail">Email</label>
                        <ContainerTextUnderline 
                            iconName="Email"
                            :text="inputsMail.currentMail" 
                            extraClass="text-[16px]"
                            id="input-current-mail"
                        />
                    </div>
                    <div class="flex flex-col w-1/4">
                        <label class="text-white font-light" for="input-new-mail">Nouvel email</label>
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
               

                <div class="w-full flex justify-center mt-8 my-3">
                    <div class=" shadow-black shadow-custom-main w-1/5">
                        <button class="text-[17px] w-full rounded-sm py-2 bg-gradient-blue 
                            rounded-br-[3px] font-light">Éditer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</template>



<script setup>
    import { ref, computed, reactive, onMounted } from 'vue';
    import { storeEmailUser } from '@/storePinia/useStoreDashboard';
    import { updateStoreUserEmail } from '@/storePinia/useUpdateStoreByBackend';
    import { sendUpdateMail } from '@/composable/useBackendActionData';
    import InputBase from '@/component/input/InputBase.vue';
    import ContainerTextUnderline from '@/component/container/ContainerTextUnderline.vue'; 
    import { isAnyMandatInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';
    
    const emailUser = storeEmailUser();
    const inputsMail = reactive({
        currentMail: '',
        newMail: '',
    });

    const errorInput = ref(false);
    const submitError = ref(null);

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
</script>