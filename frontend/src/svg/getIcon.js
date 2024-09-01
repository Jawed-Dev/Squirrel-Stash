export async function getIconByName(name) {
    switch(name) {
        case 'Restaurant' : {
            return await import('@/components/svgs/Restaurant.vue');
        }
        case 'Invisible' : {
            return await import('@/components/svgs/IconInvisible.vue');
        }
        case 'target' : {
            return await import('@/components/svgs/IconTarget.vue');
        }
        case 'balance' : {
            return await import('@/components/svgs/IconBalance.vue');
        }
        case 'Santé' : {
            return await import('@/components/svgs/IconHealth.vue');
        }
        case 'Transport' : {
            return await import('@/components/svgs/IconTransport.vue');
        }
        case 'Cadeau' : {
            return await import('@/components/svgs/IconGift.vue');
        }
        case 'Loisir' : {
            return await import('@/components/svgs/IconHobbie.vue');
        }
        case 'Famille' : {
            return await import('@/components/svgs/IconFamily.vue');
        }
        case 'Alimentation' : {
            return await import('@/components/svgs/IconFood.vue');
        }
        case 'Vestimentaire' : {
            return await import('@/components/svgs/IconClothes.vue');
        }
        case 'Loyer' : {
            return await import ('@/components/svgs/IconHouse.vue');
        }

        case 'Facture' : {
            return await import ('@/components/svgs/IconReceipt.vue');
        }

        case 'Abonnement' : {
            return await import ('@/components/svgs/IconMobile.vue');
        }

        case 'Crédit' : {
            return await import ('@/components/svgs/IconBillet.vue');
        }

        case 'Assurance' : {
            return await import ('@/components/svgs/IconCar.vue');
        }

        case 'Autre' : {
            return await import ('@/components/svgs/IconQuestionMark.vue');
        }

        case 'calculator' : {
            return await import ('@/components/svgs/IconCalculator.vue');
        }

        case 'Search' : {
            return await import ('@/components/svgs/IconSearch.vue');
        }

        case 'Amount' : {
            return await import ('@/components/svgs/IconAmount.vue');
        }

        case 'Crown' : {
            return await import ('@/components/svgs/IconCrown.vue');
        }

        case 'Pencil' : {
            return await import ('@/components/svgs/IconPencil.vue');
        }

        case 'Calendar' : {
            return await import ('@/components/svgs/IconCalendar.vue');
        }

        case 'Category' : {
            return await import ('@/components/svgs/IconCategoryTrs.vue');
        }

        case 'Name' : {
            return await import ('@/components/svgs/IconName.vue');
        }

        case 'Password' : {
            return await import ('@/components/svgs/IconPassword.vue');
        }

        case 'Email' : {
            return await import ('@/components/svgs/IconEmail.vue');
        }

        case 'Cross' : {
            return await import ('@/components/svgs/IconCross.vue');
        }

        case 'Gender' : {
            return await import ('@/components/svgs/IconGender.vue');
        }

        case 'Validation' : {
            return await import ('@/components/svgs/IconValidation.vue');
        }

        case 'ArrowUp' : {
            return await import ('@/components/svgs/IconArrowUp.vue');
        }

        case 'ArrowDown' : {
            return await import ('@/components/svgs/IconArrowDown.vue');
        }

        default : {
            return '';
        }
    }
}