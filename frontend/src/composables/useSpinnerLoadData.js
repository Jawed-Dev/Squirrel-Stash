import {ref, onUnmounted} from 'vue';


export default function useSpinnerLoadData() {
    const isLoadedData = ref(false);
    let timerId = null;
    
    function timerLoadPageSpinner(timeMountedComponent) {
        const timeForLoadData = Date.now() - timeMountedComponent;
        const MIN_TIME_LOAD = 400;
        // if the load is slow, we're not using timer
        if(timeForLoadData >= MIN_TIME_LOAD) {
            isLoadedData.value = true;
            return;
        }
        // if the load is too faster, we set a short timer to delay the loading component
        if(timerId) return;
        timerId = setInterval(() => {
            isLoadedData.value = true;
            clearTimer();
        }, 400);

    };

    function clearTimer() {
        if (timerId) {
            clearInterval(timerId);
            timerId = null;
        }
    }

    onUnmounted(() => {
        clearTimer();
    });

    return {
        isLoadedData,
        timerLoadPageSpinner
    }
}

