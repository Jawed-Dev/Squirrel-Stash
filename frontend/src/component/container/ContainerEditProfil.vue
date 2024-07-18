<template>
    <section class="relative rounded-[3px] w-[100%] text-white overflow-hidden bg-main-gradient 
        shadow-black shadow-custom-main mt-[10px]">
        <h2 class="absolute w-full mt-5 shadow-black shadow-custom-main bg-gradient-x-blue p-2 
        font-light flex justify-start text-[18px] text-white">Édition de profil</h2>

        <div class="gradient-border overflow-hidden">
            
            <form class="mt-24" @submit.prevent="handleSubmit"> 
                <!-- Errors -->
                <div class="relative pb-5">
                    <p class="text-sm font-light absolute text-red-300">{{ textError }}</p>
                </div>

                <div class="flex justify-start w-full gap-24 pl-52">
                    <div class="flex flex-col w-1/4">
                        <label class="text-white font-light" for="input-firstname">Prénom *</label>
                        <InputBase 
                            iconName="Name"
                            id="input-firstname" 
                            v-model="inputsProfil.firstName"
                            v-model:stateError="errorInputs.firstName"
                            extraClass="" 
                            :placeholder="`Prénom`"
                            type="text"
                            validFormat="firstName"
                            :hideAnimation="true"

                        />
                    </div>
                    <div class="flex flex-col w-1/4">
                        <label class="text-white font-light" for="input-lastname">Nom *</label>
                        <InputBase 
                            iconName="Name"
                            id="input-lastname" 
                            v-model="inputsProfil.lastName" 
                            v-model:stateError="errorInputs.lastName"
                            extraClass="" 
                            placeholder="Nom"
                            type="text"
                            validFormat="lastName"
                            :hideAnimation="true"
                        />
                    </div>

                    <div class="flex flex-col w-1/4">
                        <label class="text-white font-light" for="input-birthday">Date de naissance</label>
                        <InputBase 
                            id="input-birthday" 
                            v-model="inputsProfil.birthday" 
                            v-model:stateError="errorInputs.birthday"
                            extraClass="" 
                            placeholder="Mot de passe"
                            type="date"
                            validFormat="date"
                            :hideAnimation="true"
                        />
                    </div>
                </div>
                
                <div class="mt-12 flex justify-start w-full gap-24 pl-52">
                    <div class="flex flex-col w-1/4">
                        <label class="text-white font-light" for="input-gender">Genre</label>
                        <InputBase 
                            iconName="Gender"
                            id="input-gender" 
                            v-model="inputsProfil.gender" 
                            v-model:stateError="errorInputs.gender"
                            extraClass="" 
                            placeholder="Votre genre"
                            type="text"
                            validFormat="gender"
                            :hideAnimation="true"
                        />
                    </div>
                    <div class="flex flex-col w-1/4">
                        <label class="text-white font-light" for="input-pass">Rôle</label>
                        <ContainerTextUnderline 
                            iconName="Crown"
                            :text="textUserLevel" 
                            extraClass="text-[16px]"
                            id="input-current-mail"
                        />
                    </div>
                </div>
               
                <div class="w-full flex justify-center mt-8 my-3">
                    <div class="shadow-black shadow-custom-main w-[20%]">
                        <button class=
                            "text-[17px] w-full rounded-sm py-2 bg-gradient-blue 
                            rounded-br-[3px] font-light">Éditer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</template>


<script setup>
    import { ref, reactive, onMounted, computed } from 'vue';
    import ContainerTextUnderline from '@/component/container/ContainerTextUnderline.vue'; 
    import InputBase from '@/component/input/InputBase.vue';
    import { storeProfilUser } from '@/storePinia/useStoreDashboard';
    import { updateStoreUserProfil } from '@/storePinia/useUpdateStoreByBackend';
    import { updateUserProfil } from '@/composable/useBackendActionData';
    import { isAnyMandatInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';

    // Store Pinia
    const dataProfilUser = storeProfilUser();

    const inputsProfil = reactive({
        firstName: '',
        lastName: '',
        birthday: '',
        gender: '',
        roleLevel: ''
    });

    const errorInputs = reactive({
        firstName: false,
        lastName: false,
        birthday: false,
        gender: false
    });

    const submitError = ref(null);

    // life cycle, functions

    const textError = computed(() => {
        if(submitError.value === TYPE_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS) return TEXT_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS;
        else if(submitError.value === TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST) return "La requête a échoué.";
    });

    onMounted(async () => {
        await updateStoreUserProfil();
        updateEditProfil();
    });

    const textUserLevel = computed(() => {
        if(inputsProfil.roleLevel === 0) return 'Utilisateur';
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
        const response = await await updateUserProfil({
            firstName: inputsProfil.firstName,
            lastName: inputsProfil.lastName,
            birthday: inputsProfil.birthday,
            gender: inputsProfil.gender,
        });
        const isSuccessRequest = response?.isSuccessRequest;
        if(!isSuccessRequest) {
            submitError.value = TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST;
            return;
        }
        await updateStoreUserProfil();
        updateEditProfil();
        submitError.value = null;
    }

    function updateEditProfil() {
        inputsProfil.firstName = dataProfilUser.data.firstName;
        inputsProfil.lastName = dataProfilUser.data.lastName;
        inputsProfil.birthday = (dataProfilUser.data.birthday && dataProfilUser.data.birthday !== '0000-00-00') ? dataProfilUser.data.birthday : '';
        inputsProfil.gender = dataProfilUser.data.gender;
        inputsProfil.roleLevel = dataProfilUser.data.roleLevel;
    }

    function getStatesErrorInputs() {
        return {
            firstName: errorInputs.firstName,
            lastName: errorInputs.lastName,
            birthday: errorInputs.birthday,
            gender: errorInputs.gender,
        }
    }
    function getValuesMandantInputs() {
        return {
            firstName: inputsProfil.firstName,
            lastName: inputsProfil.lastName,
        }
    }
</script>