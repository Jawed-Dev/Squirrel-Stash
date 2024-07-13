<template>
    <section class="relative rounded-[3px] w-[100%] text-white overflow-hidden bg-main-gradient 
        shadow-black shadow-custom-main mt-[10px]">
        <h2 class="absolute w-full mt-5 shadow-black shadow-custom-main bg-gradient-x-blue p-2 
        font-light flex justify-start text-[18px] text-white">Édition de profil</h2>

        <div class="gradient-border overflow-hidden">

            <form class="mt-24 flex flex-col" @submit.prevent="handleSubmit"> 
                <div class="pl-10 pr-12">
                    <div class="flex gap-24">
                        <div class="flex flex-col justify-center w-[20%]">
                            <label class="text-white font-light text-[16px]" for="input-firstname">Prénom</label>
                            <InputBase 
                                unicode="🔒"
                                id="input-firstname" 
                                v-model="inputsProfil.firstName" 
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
                                v-model="inputsProfil.lastName" 
                                extraClass="" 
                                placeholder="Mot de passe"
                                type="text"
                            />
                        </div>

                        <div class="flex flex-col justify-center w-[20%]">
                            <label class="text-white font-light text-[16px]" for="input-birthday">Date de naissance</label>
                            <InputBase 
                                unicode="🔒"
                                id="input-birthday" 
                                v-model="inputsProfil.birthday" 
                                extraClass="" 
                                placeholder="Mot de passe"
                                type="date"
                            />
                        </div>
                    </div>
                </div>
                <div class="mt-12 pl-10 ">
                    <div class="flex gap-24 ">
                        <div class="flex flex-col  w-[20%]">
                            <label class="text-white font-light text-[16px]" for="input-gender">Genre</label>
                            <InputBase 
                                unicode="🔒"
                                id="input-gender" 
                                v-model="inputsProfil.gender" 
                                extraClass="" 
                                placeholder="Non défini"
                                type="text"
                            />
                        </div>

                        <div class="flex flex-col w-[20%]">
                            <label class="text-white font-light text-[16px]" for="input-pass">Rôle</label>

                            <ContainerTextUnderline 
                                unicode="🔒"
                                :text="textUserLevel" 
                                extraClass="text-[16px]"
                                id="input-current-mail"
                            />
                        </div>
                    </div>
                </div>

                <div class="w-full flex justify-center relative my-5">
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
    import { reactive, onMounted, computed } from 'vue';
    import ContainerTextUnderline from '@/component/container/ContainerTextUnderline.vue'; 
    import InputBase from '@/component/input/InputBase.vue';
    import { storeProfilUser } from '@/storePinia/useStoreDashboard';
    import { updateStoreUserProfil } from '@/storePinia/useUpdateStoreByBackend';
    import { updateUserProfil } from '@/composable/useBackendActionData';

    // Store Pinia
    const dataProfilUser = storeProfilUser();

    const inputsProfil = reactive({
        firstName: '',
        lastName: '',
        birthday: '',
        gender: '',
        roleLevel: ''
    });


    onMounted(async () => {
        await updateStoreUserProfil();
        updateEditProfil();
    });

    const textUserLevel = computed(() => {
        if(inputsProfil.roleLevel === 0) return 'Utilisateur';
    });

    async function handleSubmit() {
        await updateUserProfil({
            firstName: inputsProfil.firstName,
            lastName: inputsProfil.lastName,
            birthday: inputsProfil.birthday,
            gender: inputsProfil.gender,
        });
        await updateStoreUserProfil();
        updateEditProfil();
    }

    function updateEditProfil() {
        inputsProfil.firstName = dataProfilUser.data.firstName;
        inputsProfil.lastName = dataProfilUser.data.lastName;
        inputsProfil.birthday = (dataProfilUser.data.birthday && dataProfilUser.data.birthday !== '0000-00-00') ? dataProfilUser.data.birthday : '';
        inputsProfil.gender = dataProfilUser.data.gender;
        inputsProfil.roleLevel = dataProfilUser.data.roleLevel;
    }
</script>