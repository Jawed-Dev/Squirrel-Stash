import { onMounted, onBeforeUnmount } from 'vue';

export default function useEscapeKey(isMenuActiveRef, callback) {

    function handleClickOutside(event) {
        // Vérifie si le menu overlay est actif
        if (isMenuActiveRef.value && event.key === 'Escape') {
            callback();
        }
    }

    onMounted(() => {
        document.addEventListener('keydown', handleClickOutside, true);
    });

    onBeforeUnmount(() => {
        document.removeEventListener('keydown', handleClickOutside, true);
    });
}