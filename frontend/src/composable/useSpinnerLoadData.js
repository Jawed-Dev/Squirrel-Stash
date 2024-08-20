import {ref, onUnmounted} from 'vue';

export const isLoadedData = ref(false);
let timerId = null;

export function timerLoadPageSpinner(timeMountedComponent, refIsLoad = null) {
    const timeForLoadData = Date.now() - timeMountedComponent;
    const MIN_TIME_LOAD = 200;
    if(timeForLoadData >= MIN_TIME_LOAD) {
        isLoadedData.value = true;
        f_isLoadedData(refIsLoad);
        return;
    }
    if(timerId) return;
    timerId = setInterval(() => {
        isLoadedData.value = true;
        f_isLoadedData(refIsLoad);
        clearInterval(timerId);  
        if(timerId) clearInterval(timerId);
        timerId = null;
    }, 300);
};

function f_isLoadedData(ref) {
    if(!ref) return;
    ref.value = true;
}