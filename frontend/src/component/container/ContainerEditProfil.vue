<template>
    <section class="relative rounded-[3px] w-[100%] text-white overflow-hidden bg-main-gradient 
        shadow-black shadow-custom-main mt-[10px]">
        <div 
            @click="toggleParamsSearch"    
            class="absolute w-full mt-5 shadow-black shadow-custom-main bg-gradient-x-blue py-2 pl-3
                font-light flex justify-start gap-2 text-[18px] text-white 
                hover:shadow-main-blue cursor-pointer">
            <h1>Gestion de profil</h1>
            <UseIconLoader :svg=iconConfig :nameIcon="typeIconShowParams"  />
        </div>

        <div :class="`gradient-border overflow-hidden ${paddingForMenuOpen}`">
            
            <form class="mt-16" @submit.prevent="handleSubmit"> 
                <TransitionAxeY>
                    <div v-show="toggleShowParams">
                        <!-- Errors -->
                        <div class="relative pb-5">
                            <p class="text-sm font-light absolute text-red-300">{{ textError }}</p>
                        </div>
        
                        <div class="flex flex-col justify-center items-center gap-3
                            md:gap-20 lg:gap-32 xl:gap-48 md:flex-row">
                            <div class="flex flex-col w-1/2 min-w-[250px] lg:min-w-[350px] md:w-1/4">
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
                            <div class="flex flex-col w-1/2 min-w-[250px] lg:min-w-[350px] md:w-1/4">
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
                        </div>
        
                        <div class="flex flex-col justify-center items-center gap-3 mt-3
                            md:gap-20 lg:gap-32 xl:gap-48 md:flex-row">
                            <div class="flex flex-col w-1/2 min-w-[250px] lg:min-w-[350px] md:w-1/4">
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
                            <div class="flex flex-col w-1/2 min-w-[250px] lg:min-w-[350px] md:w-1/4">
                                <label class="text-white font-light" for="input-pass">Rôle</label>
                                <ContainerTextUnderline 
                                    iconName="Crown"
                                    :text="textUserLevel" 
                                    id="input-current-mail"
                                />
                            </div>
                        </div>
        
                        <div class="flex flex-col justify-center items-center gap-3 mt-3
                            md:gap-20 lg:gap-32 xl:gap-48 md:flex-row">
                            <div class="flex flex-col w-1/2 min-w-[250px] lg:min-w-[350px] md:w-1/4">
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
                            <div class="hidden md:flex flex-col w-1/2 min-w-[250px] lg:min-w-[350px] md:w-1/4"></div>
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
        <OverlaySuccessAction text="Votre profil a été édité." v-if="isSuccessAction" v-model:overlayActive="isSuccessAction" />
    </TransitionPopUp>
</template>


<script setup>
    import { ref, reactive, onMounted, computed, defineAsyncComponent } from 'vue';
    import ContainerTextUnderline from '@/component/container/ContainerTextUnderline.vue'; 
    import InputBase from '@/component/input/InputBase.vue';
    import { storeProfilUser } from '@/storePinia/useStoreDashboard';
    import { updateStoreUserProfil } from '@/storePinia/useUpdateStoreByBackend';
    import { updateUserProfil } from '@/composable/useBackendActionData';
    import { isAnyMandatInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';
    import TransitionPopUp from '@/component/transition/TransitionPopUp.vue';
    import TransitionAxeY from '@/component/transition/TransitionAxeY.vue';
    import UseIconLoader from '@/composable/useIconLoader.vue';
    import { setSvgConfig } from '@/svg/svgConfig';


    const OverlaySuccessAction = defineAsyncComponent(() => import('@/component/overlay/OverlaySuccessAction.vue'));

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
    const isSuccessAction = ref(false);
    const toggleShowParams= ref(false);
    const iconConfig = setSvgConfig({width:'30px', fill:'white' });

    // life cycle, functions
    const textError = computed(() => {
        if(submitError.value === TYPE_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS) return TEXT_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS;
        else if(submitError.value === TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST) return "La requête a échoué.";
    });

    onMounted(async () => {
        await updateStoreUserProfil();
        updateEditProfil();
    });

    const typeIconShowParams = computed(() => {
        return (toggleShowParams.value) ? 'ArrowUp' : 'ArrowDown';
    });

    const paddingForMenuOpen = computed(() => {
        return (toggleShowParams.value) ? '' : 'pb-5';
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
        isSuccessAction.value = true;
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

    function toggleParamsSearch() {
        toggleShowParams.value = !toggleShowParams.value;
    }
</script>