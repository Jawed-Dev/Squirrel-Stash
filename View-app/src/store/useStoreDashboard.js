import { defineStore } from 'pinia';
import { ref } from 'vue';
import { getCurrentMonthName, getCurrentYear, getMonthNumber } from '@/composable/useGetDate';

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
    const economicBalance = ref(0);
    const biggestPurchase = ref('');
    const biggestRecurring = ref('');
    return {
      totalTransactions,
      economicBalance,
      biggestPurchase,
      biggestRecurring
    };
});

// threshold
export const storeThreshold = defineStore('threshold', () => {
  const amount = ref(0);
  return { amount };
});

// list purchases/recurrings month by day
export const storeTrsMonthByDay = defineStore('listTrsMonthByDay', () => {
    const listPurchases = ref([]);
    const listRecurrings = ref([]);
  return { listPurchases, listRecurrings };
});

// list last N purchases / recurrings
export const storelastNTransactions = defineStore('lastNTransactions', () => {
  const listPurchases = ref([]);
  const listRecurrings= ref([]);
  return { listPurchases, listRecurrings };
});
