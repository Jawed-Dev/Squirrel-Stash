import { defineStore } from 'pinia';
import { ref, reactive } from 'vue';
import { getCurrentMonthName, getCurrentYear, getMonthNumber } from '@/composables/useGetDate';


export const storeAuthTOken = defineStore('authToken', () => {
    const token = ref('');
    return { token };
});

// Month & Year selected
export const storeDateSelected = defineStore('dateSelected', () => {
  const currentMonthName = getCurrentMonthName();
  const currentYear = getCurrentYear();
  const currentMonthNumber = getMonthNumber(currentMonthName);

  const month = ref(currentMonthNumber);
  const year = ref(currentYear);
  return { month, year };
});

// statsitic details
export const storeStatisticDetails = defineStore('statisticDetails' , () => {
    const totalTransactions = ref(0);
    const economyBalance = ref(0);
    const biggestPurchase = ref('');
    const biggestRecurring = ref('');
    return {
      totalTransactions,
      economyBalance,
      biggestPurchase,
      biggestRecurring
    };
});

// store threshold
export const storeThreshold = defineStore('threshold', () => {
  const amount = ref(0);
  return { amount };
});

// list purchases/recurrings month by day
export const storeTrsMonthByDay = defineStore('trsMonthByDay', () => {
    const listPurchases = ref([]);
    const listRecurrings = ref([]);
  return { listPurchases, listRecurrings };
});

// list last N purchases / recurrings
export const storeLastNTransactions = defineStore('lastNTransactions', () => {
  const listPurchases = ref([]);
  const listRecurrings= ref([]);
  return { listPurchases, listRecurrings };
});


// list Transactions by Search
export const storeDataTrsSearch = defineStore('listTrsSearch', () => {
  const dataTransactions = ref([]);
  return { dataTransactions };
});

export const storeParamsSearch = defineStore('paramsSearch', () => {
  const params = ref([]);
  return { params };
});

// store user profil
export const storeProfilUser = defineStore('dataProfilUser', () => {
  const data = reactive({
    firstName: '',
    lastName: '',
    birthday: '',
    gender: '',
    roleLevel: ''
  });

  return { data };
});

export const storeEmailUser = defineStore('dataProfilUser', () => {
  const currentEmail = ref('');

  return { currentEmail };
});

export const storePassUser = defineStore('dataPassUser', () => {
  const data = reactive({
    currentPass: '',
    newPass: '',
    confirmNewPass: ''
  });

  return { data };
});

export const storeYearListTrsByMonth = defineStore('yearListTrsByMonth', () => {
  const data = ref([]);
  return { data };
});

export const storeTextStatsByYear = defineStore('textStatsByYear', () => {
  const totalTransactions = ref(0);
  const biggestMonth = ref('');
  const biggestPurchase = ref('');
  const biggestRecurring = ref('');
  
  return {
    totalTransactions,
    biggestMonth,
    biggestPurchase,
    biggestRecurring
  };
});

export const storeYearListTrsByCategories = defineStore('yearListTrsByCategories', () => {
  const data = ref([]);
  return { data };
});

export const storeTopYearCategories = defineStore('topYearCategories', () => {
  const listPurchases = ref([]);
  const listRecurrings= ref([]);
  return { listPurchases, listRecurrings };
});




