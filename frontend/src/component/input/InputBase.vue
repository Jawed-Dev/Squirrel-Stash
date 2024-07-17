<template>
    <div>
        <div 
            :class="`pl-3 flex items-center py-[1px] ${border} transition-all duration-500 ${colorBorder} text-white font-light`">
            <div>
                <span>{{ unicode }}</span>
            </div>

            <input
                @input="onInput"
                @focus="isInputFocused = true" 
                @blur="isInputFocused = false"
                :class="`${props.extraClass} text-[15px] transition-colors duration-500 ${colorValidationInput} bg-transparent pl-1 focus:outline-none ${width} .webkit-white w-full`"
                :value="inputValue"
                :type="type"
                :placeholder="placeholder"
                :id="props.id"
            >
       
            <div :class="`transition-colors duration-500 ${colorValidationInput} mx-1 `">
                {{ iconValidationInput }}
            </div>
        </div>
    </div>
    <TransitionOpacity duration-in="duration-150" duration-out="duration-0">
        <div v-if="isAnyError" :class="`text-red-200 font-light`">
            <p class="text-sm pt-1 absolute">{{ isAnyError }}</p>
        </div>
    </TransitionOpacity>
</template>

<script setup>
    import { ref, computed, watch } from 'vue';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';
    import { typeError } from '@/error/useHandleError';
    import {
        isValidMail,
        isValidPassword,
        isValidFirstName,
        isValidLastName,
        isValidInputAmount,
        isValidInputNote,
        isValidCategory,
        isValidTrsType,
        isValidInputDate,
        isValidGender
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
        date: isValidInputDate,
        gender: isValidGender
    };
    
    const props = defineProps({
        //modelValue: { default:''},
        extraClass: { default:'' },
        id: {default:''},
        type: {default: 'text'},
        placeholder: {default: ''},
        width: {default:''},
        
        borderHidden: {default: false},
        hideAnimation: {default: false},
        isTextArea: {default: false},
        unicode: {default:''},

        validFormat: {default:''},
        dataValidFormat: {default: { } },
    });

    const inputValue = defineModel();
    const modelStateError = defineModel('stateError');
    const isInputFocused = ref(false);
    const timeoutCheckError = ref(null);

    const colorBorder = computed(() => {
        if(props.borderHidden) return '';
        if(!isInputEmpty()) {
            if(!props.hideAnimation) {
                if(isAnyError.value) return "border-red-300";
                return (!isTimerCheckErrorActive()) ? "border-green-300" : "border-custom-blue";
            }
            return "border-custom-blue";
        }
        return (isInputFocused.value) ? "border-custom-blue" : "hover:border-custom-blue";
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
        if (isInputEmpty()) return '';
        if(props.validFormat) {
            const isValidInputFunction = validators[props.validFormat];
            if(!isValidInputFunction) return '';
            if(!isValidInputFunction(inputValue.value)) {
                modelStateError.value = true;
                if(isTimerCheckErrorActive()) return '';
                return typeError[props.validFormat].adviceFormat;
            }
            else modelStateError.value = false;
        }
        return '';
    });

    //life cycle functions
    watch(inputValue, (newValue) => {
        if(isTimerCheckErrorActive()) clearTimeout(timeoutCheckError.value);
        timeoutCheckError.value = setTimeout(() => {
            timeoutCheckError.value = null;
            console.log('debouncing', timeoutCheckError.value);
         }, 1000);
    });

    function isInputEmpty() {
        return inputValue.value === '';
    }

    function onInput(event) {
        inputValue.value = event.target.value;
        console.log(inputValue.value);
    }

    const border = computed(() => {
        return (!props.borderHidden) ? 'border-b' : 'border-none';
    });

    function isTimerCheckErrorActive() {
        return timeoutCheckError.value;
    }
</script>


