<template>
    <div 
        :class="`bg-second-bg z-10 px-2 flex border-2 items-center py-[1px] rounded-lg mt-[2px]
        ${border} transition-all duration-500 ${colorBorder} text-white font-light`">
        <div v-show="iconName"> 
            <UseIconLoader :nameIcon="iconName" :svg="{width:'20px', fill:'rgba(255, 255, 255, 1)'}"  />
        </div>

        <input
            @input="onInput"
            @focus="isInputFocused = true" 
            @blur="isInputFocused = false"
            :class="`p-1 pl-2 bg-second-bg text-[15px] ${props.extraClass} transition-colors 
            duration-500 ${colorValidationInput} focus:outline-none ${width} .webkit-white w-full`"
            :value="inputValue"
            :type="type"
            :placeholder="placeholder"
            :id="props.id"
        >
    
        <div :class="`transition-colors duration-500 ${colorValidationInput} mx-1 `">
            {{ iconValidationInput }}
        </div>
    </div>
    <TransitionOpacity duration-in="duration-150" duration-out="duration-0">
        <div v-if="isAnyError" :class="`text-red-200 font-light relative`">
            <p class="text-sm mt-[1px] absolute">{{ isAnyError }}</p>
            <!-- <div title="Format valide : exemple@domaine.com" class="text-white mt-[6px] absolute left-[106px] bg-gradient-vanusa shadow-main hover:bg-custom-blue p-1 rounded-full cursor-pointer"> 
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" />
                    <path d="M12 19l0 .01" />
                </svg>
            </div> -->
        </div>
    </TransitionOpacity>
</template>

<script setup>
    import { ref, computed, watch } from 'vue';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';
    import { typeError } from '@/error/useHandleError';
    import UseIconLoader from '@/composable/useIconLoader.vue';
    import { createToast } from '@/composable/useToastNotification';

    import {
        isValidMail,
        isValidPassword,
        isValidFirstName,
        isValidLastName,
        isValidInputAmount,
        isValidInputNote,
        isValidCategory,
        isValidTrsType,
        isValidDate,
        isValidTrsDate,
        isValidGender,
        isValidMessage
    } from '@/error/useValidFormat';

    // variables, props, ...
    const validators = {
        email: isValidMail,
        password: isValidPassword,
        firstName: isValidFirstName,
        lastName: isValidLastName,
        amount: isValidInputAmount,
        note: isValidInputNote,
        category: isValidCategory,
        transactionType: isValidTrsType,
        date: isValidDate,
        trsDate: isValidTrsDate,
        gender: isValidGender,
        message: isValidMessage
    };
    
    const props = defineProps({
        extraClass: { default:'' },
        id: {default:''},
        type: {default: 'text'},
        placeholder: {default: ''},
        width: {default:''},
        iconName: {default: ''},
        onlyNumbers: false,
        
        borderHidden: {default: false},
        hideAnimation: {default: false},
        isTextArea: {default: false},
        unicode: {default:''},

        validFormat: {default:''},
        dataValidFormat: {default: { } },
    });

    const inputValue = defineModel();
    const modelStateError = defineModel('stateError');
    const isInputMantadoryEmpty = defineModel('mandatoryInput');
    const isInputFocused = ref(false);
    const timeoutCheckError = ref(null);

    const colorBorder = computed(() => {
        if(props.borderHidden) return '';
        if(!isInputEmpty()) {
            //if(!props.hideAnimation) {
            if(isAnyError.value) return "border-red-300";
            return (!isTimerCheckErrorActive()) ? "border-green-300" : "border-custom-blue";
            /*}
            return "border-custom-blue";*/
        }
        if(isInputMantadoryEmpty.value) return "border-custom-orange";
        return (isInputFocused.value) ? "border-custom-blue" : "hover:border-custom-blue border-slate-600";
    });

    const colorValidationInput = computed(() => {
        if(props.hideAnimation) return '';
        if(isInputEmpty()) return '';
        if(isTimerCheckErrorActive()) return '';
        return (!isTimerCheckErrorActive() && isAnyError.value) ? "text-red-300 webkit-red" : "text-green-300 webkit-green";
    });

    const iconValidationInput = computed(() => {
        if(props.hideAnimation) return '';
        if(isInputEmpty()) return '';
        if(isTimerCheckErrorActive()) return '';
        return (!isTimerCheckErrorActive() && isAnyError.value) ? "✗" : "✓";
    });

    const isAnyError = computed(() => {
        if(isInputEmpty()) {
            modelStateError.value = false;
            return '';
        }
        if(isInputMantadoryEmpty.value) isInputMantadoryEmpty.value = false;
        if(props.validFormat) {
            const isValidInputFunction = validators[props.validFormat];
            if(!isValidInputFunction) return '';
            if(!isValidInputFunction(inputValue.value)) {
                modelStateError.value = true;
                if(isTimerCheckErrorActive()) return '';
                return typeError[props.validFormat].message;
            }
            else modelStateError.value = false;
        }
        return '';
    });

    //life cycle functions
    watch(inputValue, () => {
        if(isTimerCheckErrorActive()) clearTimeout(timeoutCheckError.value);
        //if(modelStateError.value) return;
        timeoutCheckError.value = setTimeout(() => {
            timeoutCheckError.value = null;
            console.log('debouncing', timeoutCheckError.value);
         }, 1000);
    });

    watch(isAnyError, () => {
        if(isAnyError.value) createToast(typeError[props.validFormat].adviceFormat, 'error');
    })

    function isInputEmpty() {
        return inputValue.value === '';
    }

    function onInput(event) {
        if(props.onlyNumbers && event.target.value.trim() !== '') {
            if(!isValidInputAmount(event.target.value)) {
                event.target.value = inputValue.value;
                return;
            }
        }
        inputValue.value = event.target.value;
    }

    const border = computed(() => {
        return (!props.borderHidden) ? 'border' : 'border-none';
    });

    function isTimerCheckErrorActive() {
        return timeoutCheckError.value;
    }
</script>


