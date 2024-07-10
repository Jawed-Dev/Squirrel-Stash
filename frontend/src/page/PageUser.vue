<template>
    <div class="font-main-font flex flex-col bg-main-bg w-full min-h-screen">
        <div class="ml-[calc(20px+70px+20px)] mr-custom-margin-main flex flex-col mt-[20px]">
            <h1 class="font-light flex justify-start text-[25px] text-white">Page Utilisateur</h1>

            <section class="w-[80%] gradient-border text-white overflow-hidden bg-main-gradient pl-3 shadow-black shadow-custom-main mt-[10px]">
                <h2 class="p-2 font-light flex justify-start text-[18px] text-white">Édition de profil</h2>
                
                <form class="pl-[15%] py-3 flex flex-col" @submit.prevent="handleSubmit('editProfil')">
                    
                    <div>
                        <div class="flex gap-[100px] mt-[6à]">
                            <div class="flex flex-col justify-center w-[20%]">
                                <label class="text-white font-light text-[16px]" for="input-firstname">Prénom</label>
                                <InputBase 
                                    unicode="🔒"
                                    id="input-firstname" 
                                    v-model="dataInputs.firstName" 
                                    extraClass="" 
                                    :placeholder="`test`"
                                    type="text"
                                />
                            </div>
        
                            <div class="flex flex-col justify-center w-[20%]">
                                <label class="text-white font-light text-[16px]" for="input-lastname">Nom</label>
                                <InputBase 
                                    unicode="🔒"
                                    id="input-lastname" 
                                    v-model="dataInputs.lastName" 
                                    extraClass="" 
                                    placeholder="Mot de passe"
                                    type="text"
                                />
                            </div>
    
                            <div class="flex flex-col justify-center w-[20%]">
                                <label class="text-white font-light text-[16px]" for="input-pass">Date de naissance</label>
                                <InputBase 
                                    unicode="🔒"
                                    id="input-pass" 
                                    v-model="dataInputs.birthday" 
                                    extraClass="" 
                                    placeholder="Mot de passe"
                                    type="date"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="mt-[60px]">
                        <div class="flex gap-[100px] ">
                            <div class="flex flex-col  w-[20%]">
                                <label class="text-white font-light text-[16px]" for="input-gender">Genre</label>
                                <InputBase 
                                    unicode="🔒"
                                    id="input-gender" 
                                    v-model="dataInputs.gender" 
                                    extraClass="" 
                                    placeholder="Non défini"
                                    type="text"
                                />
                            </div>
    
                            <div class="flex flex-col w-[20%]">
                                <label class="text-white font-light text-[16px]" for="input-pass">Rôle</label>
                                <p class="text-[16px] pl-3 flex items-center w-full py-1 border-b text-white font-light" >{{ textUserLevel }}</p>
                            </div>
                        </div>
                    </div>
    
                    <div class="w-full flex justify-center">
                        <div class="mt-[60px] shadow-black shadow-custom-main w-[25%]">
                            <button class=
                                "text-[17px] w-full rounded-sm py-2 bg-gradient-blue 
                                rounded-br-[3px] font-light">Éditer
                            </button>
                        </div>
                    </div>
                </form>
            </section>

            <section class="w-[80%] gradient-border text-white overflow-hidden bg-main-gradient pl-3 shadow-black shadow-custom-main mt-[20px]">
                <h2 class="p-2 font-light flex justify-start text-[18px] text-white">Édition d'email</h2>
                
                <form class="pl-[15%] py-3 flex flex-col" @submit.prevent="handleSubmit('editMail')">
                    
                    <div>
                        <div class="flex gap-[100px]">
                            <div class="flex flex-col justify-center w-[30%]">
                                <label class="text-white font-light text-[16px]" for="input-current-mail">Email actuel</label>
                                <InputBase 
                                    unicode="🔒"
                                    id="input-current-mail" 
                                    v-model="dataInputs.roleLevel" 
                                    extraClass="" 
                                    placeholder="Mot de passe"
                                    type="password"
                                />
                            </div>

                            <div class="flex flex-col  w-[30%]">
                                <label class="text-white font-light text-[16px]" for="input-pass">Nouveau email</label>
                                <InputBase 
                                    unicode="🔒"
                                    id="input-pass" 
                                    v-model="dataInputs.roleLevel" 
                                    extraClass="" 
                                    placeholder="Mot de passe"
                                    type="password"
                                />
                            </div>
                        </div>
                    </div>
    
                    <div class="w-full flex justify-center">
                        <div class="mt-[40px] shadow-black shadow-custom-main w-[25%]">
                            <button class=
                                "text-[17px] w-full rounded-sm py-2 bg-gradient-blue 
                                rounded-br-[3px] font-light">Éditer
                            </button>
                        </div>
                    </div>
                </form>
            </section>

            <section class="w-[80%] gradient-border text-white overflow-hidden bg-main-gradient pl-3 shadow-black shadow-custom-main mt-[20px]">
                <h2 class="p-2 font-light flex justify-start text-[18px] text-white">Édition de mot de passe</h2>
                
                <form class="pl-[15%] py-3 flex flex-col" @submit.prevent="handleSubmit('editPass')">
                    
                    <div>
                        <div class="flex gap-[100px]">
                            <div class="flex flex-col justify-center w-[30%]">
                                <label class="text-white font-light text-[16px]" for="input-pass">Mot de passe actuel</label>
                                <InputBase 
                                    unicode="🔒"
                                    id="input-pass" 
                                    v-model="dataInputs.roleLevel" 
                                    extraClass="" 
                                    placeholder="Mot de passe"
                                    type="password"
                                />
                            </div>
        
                            <div class="flex flex-col justify-center w-[30%]">
                                <label class="text-white font-light text-[16px]" for="input-pass">Force du mot de passe</label>
                            </div>

                        </div>
                    </div>
                    <div class="mt-[60px]">
                        <div class="flex gap-[100px] ">
                            <div class="flex flex-col  w-[30%]">
                                <label class="text-white font-light text-[16px]" for="input-confirm-pass">Nouveau mot de passe</label>
                                <InputBase 
                                    unicode="🔒"
                                    id="input-confirm-pass" 
                                    v-model="dataInputs.roleLevel" 
                                    extraClass="" 
                                    placeholder="Mot de passe"
                                    type="password"
                                />
                            </div>
    
                            <div class="flex flex-col w-[30%]">
                                <label class="text-white font-light text-[16px]" for="input-pass">Confirmation du nouveau mot de passe</label>
                                <InputBase 
                                    unicode="🔒"
                                    id="input-pass" 
                                    v-model="dataInputs.roleLevel" 
                                    extraClass="" 
                                    placeholder="Mot de passe"
                                    type="password"
                                />
                            </div>
                        </div>
                    </div>
    
                    <div class="w-full flex justify-center">
                        <div class="mt-[40px] shadow-black shadow-custom-main w-[25%]">
                            <button class=
                                "text-[17px] w-full rounded-sm py-2 bg-gradient-blue 
                                rounded-br-[3px] font-light">Éditer
                            </button>
                        </div>
                    </div>
                </form>
            </section>
            
        </div>

        
    </div>
</template>



<script setup>
    import { ref, onMounted, computed, reactive } from 'vue';
    import InputBase from '@/component/input/InputBase.vue';
    import { storeDataProfilUser } from '@/storePinia/useStoreDashboard';
    import { updateStoreUserProfil } from '@/storePinia/useUpdateStoreByBackend';
    import { updateDataUserProfil } from '@/composable/useBackendActionData';

    // Store Pinia
    const dataProfilUser = storeDataProfilUser();

    // ref, variables ....
    const dataInputs = reactive({
        firstName: '',
        lastName: '',
        birthday: '',
        gender: '',
        roleLevel: ''
    });

    // life cycle, functions
    onMounted(async () => {
        await updateStoreUserProfil();
        updateEditProfil();
    });

    const textUserLevel = computed(() => {
        if(dataInputs.roleLevel === 0) return 'Utilisateur';
    });

    async function handleSubmit(request) {
        switch(request) {
            case 'editProfil' : {
                await updateDataUserProfil({
                    firstName: dataInputs.firstName,
                    lastName: dataInputs.lastName,
                    birthday: dataInputs.birthday,
                    gender: dataInputs.gender,
                });
                await updateStoreUserProfil();
                updateEditProfil();
            }
        }
    }


    function updateEditProfil() {
        dataInputs.firstName = dataProfilUser.data.firstName;
        dataInputs.lastName = dataProfilUser.data.lastName;
        dataInputs.birthday = (dataProfilUser.data.birthday && dataProfilUser.data.birthday !== '0000-00-00') ? dataProfilUser.data.birthday : '';
        dataInputs.gender = dataProfilUser.data.gender;
        dataInputs.roleLevel = dataProfilUser.data.roleLevel;
    }
</script>