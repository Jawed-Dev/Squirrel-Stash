<template>
    <section class="relative rounded-[3px] w-full text-white overflow-hidden bg-main-gradient  
    shadow-black shadow-custom-main mt-[20px]">
    <h2 class="absolute w-full mt-5 shadow-black shadow-custom-main bg-gradient-x-blue p-2 
    font-light flex justify-start text-[18px] text-white">Édition d'email</h2>
    
        <div class="gradient-border overflow-hidden">
            <form class="mt-24 flex flex-col" @submit.prevent="handleSubmit()">
                <div class="pl-12 w-1/2">
                    <div class="flex gap-12">
                        <div class="flex flex-col justify-center w-1/2">
                            <label class="text-white font-light text-[16px]" for="input-current-mail">Email</label>
                            <ContainerTextUnderline 
                                unicode="🔒"
                                :text="inputsMail.currentMail" 
                                extraClass="text-[16px]"
                                id="input-current-mail"
                            />
                        </div>

                        <div class="flex flex-col w-1/2">
                            <label class="text-white font-light text-[16px]" for="input-new-mail">Nouvel email</label>
                            <InputBase 
                                unicode="🔒"
                                id="input-new-mail" 
                                v-model="inputsMail.newMail" 
                                extraClass="" 
                                placeholder="Non défini"
                                type="mail"
                            />
                        </div>
                    </div>
                </div>

                <div class="w-full flex justify-center relative my-5">
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
    import { reactive, onMounted } from 'vue';
    import { storeEmailUser } from '@/storePinia/useStoreDashboard';
    import { updateStoreUserEmail } from '@/storePinia/useUpdateStoreByBackend';
    import { sendUpdateMail } from '@/composable/useBackendActionData';
    import InputBase from '@/component/input/InputBase.vue';
    import ContainerTextUnderline from '@/component/container/ContainerTextUnderline.vue'; 
    
    const emailUser = storeEmailUser();
    const inputsMail = reactive({
        currentMail: '',
        newMail: '',
    });

    // life cycle, functions
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
        await sendUpdateMail({
            newEmail: inputsMail.newMail
        });
        await updateStoreUserEmail();
        updateEditEmail();
        clearInputsEmail();
    }
</script>