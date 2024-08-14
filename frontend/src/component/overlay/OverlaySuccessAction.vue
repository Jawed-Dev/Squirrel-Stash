<template>
    <div class="fixed inset-0 bg-black bg-opacity-80 z-30">
        <div v-if="isOverlayActive" 
        :class="`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-30 text-white rounded-[3px] overflow-hidden 
         shadow-main trigger-action-success bg-main-gradient w-[40%]`">
            <MainContainerSlot 
            :hideCross="true"
            :onlyOneBtn="true"
            :textBtn1="'Ok'" 
            :titleContainer="'Action Réussie'" 
            @toggleMenu="toggleMenu" 
            >
                <div class="my-12">
                    <div class="flex justify-center w-full p5">
                        <UseIconLoader nameIcon="Validation" :svg="{width:'150px', fill:'green'}"  />
                    </div>

                    <div class="flex justify-center mt-12">
                        <h2 class="text-lg">{{text}}</h2>
                    </div>
                </div>
            </MainContainerSlot>
        </div>
    </div>
</template>


<script setup>
    import { ref, watch, computed, reactive, defineAsyncComponent } from 'vue';
    import MainContainerSlot from '@/component/containerSlot/MainContainerSlot.vue';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';
    import UseIconLoader from '@/composable/useIconLoader.vue';
    import { useRouter } from 'vue-router';

    // variables, props
    const router = useRouter();
    const props = defineProps({
        text: {default: ''},
        redirect: {default: ''}
    });
    const isOverlayActive = defineModel('overlayActive');

    // life cycle, functions
    // useEscapeKey(isOverlayActive, () => {
    //     closeOverlay();
    // });

    // useClickOutside('.trigger-action-success', isOverlayActive, () => {
    //     closeOverlay();
    // });

    function closeOverlay() {
        isOverlayActive.value = false;
        if(props.redirect === 'connexion') router.push('/connexion')
    }

    async function toggleMenu(request) {
        switch(request) {
            case 'cancel': {
                closeOverlay();
                break;
            }
        }
    }
    
</script>