export async function getIconByName(name) {
    switch(name) {
        case 'restaurant' : {
            return await import('../../components/svgs/Restaurant.vue');
        }
        case 'purchaseFoods' : {
            return await import('../../components/svgs/PurchaseFoods.vue');
        }
        case 'target' : {
            return await import('../../components/svgs/Target.vue');
        }

        case 'balance' : {
            return await import('../../components/svgs/IconBalance.vue');
        }
        default : {
            return await import('../../components/svgs/Restaurant.vue');
        }

    }
}