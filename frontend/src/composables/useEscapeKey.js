import { onMounted, onBeforeUnmount } from 'vue';

export default function useEscapeKey(isMenuActiveRef, callback) {

    function handlePushEscapeKey(event) {
        // verify if the overlay is active
        if (isMenuActiveRef.value && event.key === 'Escape') {
            callback();
        }
    }
    onMounted(() => {
        document.addEventListener('keydown', handlePushEscapeKey, true);
    });
    onBeforeUnmount(() => {
        document.removeEventListener('keydown', handlePushEscapeKey, true);
    });
}