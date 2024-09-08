<template>
    <section class="relative rounded-[3px] w-full text-white overflow-hidden bg-main-gradient 
         shadow-main mt-[10px]">
        <div 
            @click="toggleParamsSearch"    
            class="absolute w-full mt-3 shadow-main bg-gradient-x-blue py-2 pl-3
                font-light flex justify-start gap-2 text-[18px] text-white 
                hover:shadow-slate-500 cursor-pointer">

            <UseIconLoader :svg=iconConfig :nameIcon="typeIconShowParams"  />
            <h1>Gestion de profil</h1>
        </div>

        <div :class="`gradient-border overflow-hidden ${paddingForMenuOpen}`">
            
            <form class="mt-16" @submit.prevent="handleSubmit"> 
                <TransitionAxeY>
                    <div v-show="toggleShowParams">
                        <div class="xl:flex w-full">

                            <div class="xl:w-[40%] 2xl:w-[45%] flex justify-center items-center">
                                <ImageEditProfil class="pt-5 w-full" :svg="sizeImage" />
                            </div>

                            <div class="mt-2 grow flex flex-col justify-center">
                                <div class="xl:mt-5 w-full flex">
                                    <div class="w-full flex flex-col justify-center xl:justify-center 2xl:justify-evenly 
                                            items-center gap-2 sm:gap-12 2xl:gap-0 sm:flex-row">
    
                                        <div class="flex flex-col w-1/3 min-w-[270px] lg:min-w-[280px] 2xl:min-w-[300px] md:w-1/4">
                                            <label class="pl-2 text-white font-light" for="input-firstname">Prénom</label>
                                            <InputBase 
                                                iconName="Name"
                                                id="input-firstname" 
                                                v-model="inputsProfil.firstName"
                                                v-model:stateError="errorInputs.firstName"
                                                v-model:mandatoryInput="mandatoryInputs.firstName"
                                                extraClass="" 
                                                :placeholder="`Prénom`"
                                                type="text"
                                                validFormat="firstName"
                                                :hideAnimation="true"
                    
                                            />
                                        </div>
                                        <div class="flex flex-col w-1/3 min-w-[270px] lg:min-w-[280px] 2xl:min-w-[300px] md:w-1/4">
                                            <label class="pl-2 text-white font-light" for="input-lastname">Nom</label>
                                            <InputBase 
                                                iconName="Name"
                                                id="input-lastname" 
                                                v-model="inputsProfil.lastName" 
                                                v-model:stateError="errorInputs.lastName"
                                                v-model:mandatoryInput="mandatoryInputs.lastName"
                                                extraClass="" 
                                                placeholder="Nom"
                                                type="text"
                                                validFormat="lastName"
                                                :hideAnimation="true"
                                            />
                                        </div>
                                    </div>
                                </div>
                
                                <div class="mt-5 w-full flex">
                                    <div class="w-full flex flex-col justify-center xl:justify-center 2xl:justify-evenly 
                                            items-center gap-2 sm:gap-12 2xl:gap-0 sm:flex-row">

                                        <div class="flex flex-col w-1/3 min-w-[270px] lg:min-w-[280px] 2xl:min-w-[300px] md:w-1/4">
                                            <label class="pl-2 text-white font-light" for="input-gender">Genre</label>
                                            <select v-model="inputsProfil.gender" 
                                                :class="`font-light pl-2 py-[2px] gradient-border text-white 
                                                    min-w-[270px] lg:min-w-[280px] 2xl:min-w-[300px] md:w-1/4
                                                    rounded-[3px] bg-main-gradient  shadow-main outline-none cursor-pointer hover:shadow-custom-lower`"
                                                >
                                                <option 
                                                    class="bg-main-bg font-light" v-for="(category, index) 
                                                    of GenderCategories" :key="index" :value="category">{{ category }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="flex flex-col w-1/3 min-w-[270px] lg:min-w-[280px] 2xl:min-w-[300px] md:w-1/4">
                                            <label class="pl-2 text-white font-light" for="input-pass">Rôle</label>
                                            <ContainerTextUnderline 
                                                iconName="Crown"
                                                :text="textUserLevel" 
                                                id="input-current-mail"
                                            />
                                        </div>
                                    </div>
                                </div>
                
                                <div class="mt-5 w-full flex">
                                    <div class="w-full flex flex-col justify-center xl:justify-center 2xl:justify-evenly 
                                            items-center gap-2 sm:gap-12 2xl:gap-0 sm:flex-row">
                                            
                                        <div class="flex flex-col w-1/3 min-w-[270px] lg:min-w-[280px] 2xl:min-w-[300px] md:w-1/4">
                                            <label class="pl-2 text-white font-light" for="input-birthday">Date de naissance</label>
                                            <InputBase 
                                                id="input-birthday" 
                                                v-model="inputsProfil.birthday" 
                                                v-model:stateError="errorInputs.birthday"
                                                extraClass="" 
                                                placeholder="Mot de passe"
                                                type="date"
                                                validFormat="date"
                                                :hideAnimation="true"
                                                iconName="Calendar"
                                            />
                                        </div>
                                        <div class="flex flex-col w-1/3 min-w-[270px] lg:min-w-[280px] 2xl:min-w-[300px] md:w-1/4"></div>
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
    import { ref, reactive, onMounted, computed } from 'vue';
    import ContainerTextUnderline from '@/components/container/ContainerTextUnderline.vue'; 
    import InputBase from '@/components/input/InputBase.vue';
    import { storeProfilUser } from '@/storesPinia/useStoreDashboard';
    import { updateStoreUserProfil } from '@/storesPinia/useUpdateStoreByBackend';
    import { updateUserProfil } from '@/requests/useBackendAction';
    import { isAnyMandatoryInputEmpty, isAnyInputError, TEXT_SUBMIT_ERROR } from '@/errors/useHandleError';
    import TransitionAxeY from '@/components/transition/TransitionAxeY.vue';
    import UseIconLoader from '@/composables/useIconLoader.vue';
    import ImageEditProfil from '@/components/svg/ImageEditProfil.vue';
    import { setSvgConfig } from '@/svgUtils/svgConfig';
    import { createToast } from '@/composables/useToastNotification';
    import { getScreenSize } from '@/composables/useSizeScreen';

    
    
    // Store Pinia
    const dataProfilUser = storeProfilUser();

    const inputsProfil = reactive({
        firstName: '',
        lastName: '',
        birthday: '',
        gender: '',
        roleLevel: '',
    });

    const GenderCategories = ['Homme', 'Femme', 'Non défini'];

    const errorInputs = reactive({
        firstName: false,
        lastName: false,
        birthday: false,
        gender: false
    });

    const mandatoryInputs = reactive({
        firstName: false,
        lastName: false,
    });
    
    const { widthScreenValue } = getScreenSize();
    const toggleShowParams= ref(false);
    const iconConfig = setSvgConfig({width:'30px', fill:'white' });
    const imageConfigBig = setSvgConfig({width:'300px', fill:'white' });
    const imageConfig = setSvgConfig({width:'240px', fill:'white' });

    // life cycle, functions
    onMounted(async () => {
        await updateStoreUserProfil();
        updateEditProfil();
    });

    const sizeImage = computed(() => {
        return widthScreenValue.value < 1024 ? imageConfig : imageConfigBig;
    })

    const typeIconShowParams = computed(() => {
        return (toggleShowParams.value) ? 'ArrowUp' : 'ArrowDown';
    });

    const paddingForMenuOpen = computed(() => {
        return (toggleShowParams.value) ? '' : 'pb-1';
    });

    const textUserLevel = computed(() => {
        if(inputsProfil.roleLevel === 0) return 'Utilisateur';
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
        const response = await await updateUserProfil({
            firstName: inputsProfil.firstName,
            lastName: inputsProfil.lastName,
            birthday: inputsProfil.birthday,
            gender: inputsProfil.gender,
        });
        const isSuccessRequest = response?.isSuccessRequest;
        if(!isSuccessRequest) {
            return;
        }
        await updateStoreUserProfil();
        createToast('Votre profil a été édité.', 'success');
        updateEditProfil();
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
    function activeErrorMandatInputs() {
        if (!inputsProfil.firstName) mandatoryInputs.firstName = true;
        if (!inputsProfil.lastName) mandatoryInputs.lastName = true;
    }
    function toggleParamsSearch() {
        toggleShowParams.value = !toggleShowParams.value;
    }
</script>