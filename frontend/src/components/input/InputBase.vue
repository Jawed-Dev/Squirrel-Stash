<template>
    <div @click="openDatePicker"
        :class="`z-10 bg-second-bg px-2 flex border-2 items-center py-[1px] rounded-lg mt-[2px] 
        transition-all duration-500 text-white font-light ${border} ${colorBorder} ${cursorPointerForDate}`">

        <!-- Icon -->
        <div v-show="iconName"> 
            <UseIconLoader :nameIcon="iconName" :svg="{width:'20px', fill:'rgba(255, 255, 255, 1)'}"  />
        </div>

        <!-- input Element -->
        <input @input="onInput" @focus="isInputFocused = true" @blur="isInputFocused = false"
            :class="`p-1 bg-transparent pl-2 ${cursorPointerForDate} text-[15px] ${props.extraClass} transition-colors 
            duration-500 ${colorValidationInput} focus:outline-none ${width} w-full`"

            :value="inputValue"
            :type="inputType"
            :placeholder="placeholder"
            :id="props.id"
            ref="refInput"
        >
      
        <!-- icon Validation if is active -->
        <div v-if="validFormat !== 'password'" :class="`transition-colors bg-transparent duration-500 ${colorValidationInput} mx-1`">
            {{ iconValidationInput }}
        </div>

        <IconEye @click="toggleHidePass" v-if="validFormat === 'password' && !isPasswordHide" class="cursor-pointer mr-1" :svg="{width:25}" />
        <IconEyeCut @click="toggleHidePass" v-if="validFormat === 'password' && isPasswordHide" class="cursor-pointer mr-1" :svg="{width:25}" />
    </div>
    <TransitionOpacity duration-in="duration-150" duration-out="duration-0">
        <div v-if="isAnyError" :class="`text-red-200 font-light relative flex pt-2`">
            <p class="text-sm">{{ isAnyError }} </p>
            <IconQuestionMark  @click="openFormatAdvice" v-if="isValidTypeInputForAdvice"
            class="ml-2 bg-gradient-vanusa rounded-full p-1 shadow-main cursor-pointer hover:opacity-50" :svg={width:20} />
        </div>
    </TransitionOpacity>
</template>

<script setup>
    import { ref, computed, watch } from 'vue';
    import TransitionOpacity from '@/components/transition/TransitionOpacity.vue';
    import { typeError } from '@/errors/useHandleError';
    import UseIconLoader from '@/composables/useIconLoader.vue';
    import { createToast } from '@/composables/useToastNotification';
    import IconQuestionMark from '@/components/svg/IconQuestionMark.vue';
    import IconEye from '@/components/svg/IconEye.vue';
    import IconEyeCut from '@/components/svg/IconEyeCut.vue';
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
        isValidMessage,
        formatInputAmount,
        isValidInputInt
    } from '@/errors/useValidFormat';




    // variables, props, ...
    const refInput = ref(null);
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
        message: isValidMessage,
        onlyInt: isValidInputInt,
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
    const isPasswordHide = ref(false);

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

    const cursorPointerForDate = computed(() => {
        return props.type === 'date' ? 'cursor-pointer' : '';
    });

    const colorValidationInput = computed(() => {
        if(props.hideAnimation) return '';
        if(isInputEmpty()) return '';
        if(isTimerCheckErrorActive()) return '';
        return (!isTimerCheckErrorActive() && isAnyError.value) ? "text-red-300 webkit-red" : "text-green-300 webkit-green";
    });

    const inputType = computed(() => {
        if (props.validFormat === 'password' && isPasswordHide.value) return 'text';
        return props.type;
    });

    const iconValidationInput = computed(() => {
        if(props.hideAnimation) return '';
        if(isInputEmpty()) return '';
        if(isTimerCheckErrorActive()) return '';
        // i removed this option
        // return (!isTimerCheckErrorActive() && isAnyError.value) ? "✗" : "✓";
        return '';
    });

    const isValidTypeInputForAdvice = computed(() => {
        return true;
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
        timeoutCheckError.value = setTimeout(() => {
            timeoutCheckError.value = null;
         }, 2000);
    });

    function openFormatAdvice() {
        createToast(typeError[props.validFormat].adviceFormat, 'error');
    }

    // watch(isAnyError, () => {
    //     if(isAnyError.value) createToast(typeError[props.validFormat].adviceFormat, 'error');
    // })

    function toggleHidePass () {
        isPasswordHide.value = !isPasswordHide.value;
    }

    function isInputEmpty() {
        return inputValue.value === '';
    }

    function onInput(event) {
        if(props.onlyNumbers && event.target.value.trim() !== '') {
            if(!formatInputAmount(event.target.value)) {
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

    const openDatePicker = () => {
        if(props.type !== 'date') return;
        if (refInput.value) refInput.value.showPicker();
    };

    
</script>


