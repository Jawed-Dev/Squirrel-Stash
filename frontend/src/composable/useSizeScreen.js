import { ref, onMounted, onUnmounted } from 'vue';

export function getScreenSize() {
    const widthScreenValue = ref(window.innerWidth);

    function handleResize() {
        widthScreenValue.value = window.innerWidth;
    }

    onMounted(() => {
        window.addEventListener('resize', handleResize);
    });

    onUnmounted(() => {
        window.removeEventListener('resize', handleResize);
    });

    return {
        widthScreenValue
    };
}