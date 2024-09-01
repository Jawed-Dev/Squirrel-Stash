export async function getIconByName(name) {
    switch(name) {
        case 'Restaurant' : {
            return await import('@/components/svg/Restaurant.vue');
        }
        case 'Invisible' : {
            return await import('@/components/svg/IconInvisible.vue');
        }
        case 'target' : {
            return await import('@/components/svg/IconTarget.vue');
        }
        case 'balance' : {
            return await import('@/components/svg/IconBalance.vue');
        }
        case 'Santé' : {
            return await import('@/components/svg/IconHealth.vue');
        }
        case 'Transport' : {
            return await import('@/components/svg/IconTransport.vue');
        }
        case 'Cadeau' : {
            return await import('@/components/svg/IconGift.vue');
        }
        case 'Loisir' : {
            return await import('@/components/svg/IconHobbie.vue');
        }
        case 'Famille' : {
            return await import('@/components/svg/IconFamily.vue');
        }
        case 'Alimentation' : {
            return await import('@/components/svg/IconFood.vue');
        }
        case 'Vestimentaire' : {
            return await import('@/components/svg/IconClothes.vue');
        }
        case 'Loyer' : {
            return await import ('@/components/svg/IconHouse.vue');
        }

        case 'Facture' : {
            return await import ('@/components/svg/IconReceipt.vue');
        }

        case 'Abonnement' : {
            return await import ('@/components/svg/IconMobile.vue');
        }

        case 'Crédit' : {
            return await import ('@/components/svg/IconBillet.vue');
        }

        case 'Assurance' : {
            return await import ('@/components/svg/IconCar.vue');
        }

        case 'Autre' : {
            return await import ('@/components/svg/IconQuestionMark.vue');
        }

        case 'calculator' : {
            return await import ('@/components/svg/IconCalculator.vue');
        }

        case 'Search' : {
            return await import ('@/components/svg/IconSearch.vue');
        }

        case 'Amount' : {
            return await import ('@/components/svg/IconAmount.vue');
        }

        case 'Crown' : {
            return await import ('@/components/svg/IconCrown.vue');
        }

        case 'Pencil' : {
            return await import ('@/components/svg/IconPencil.vue');
        }

        case 'Calendar' : {
            return await import ('@/components/svg/IconCalendar.vue');
        }

        case 'Category' : {
            return await import ('@/components/svg/IconCategoryTrs.vue');
        }

        case 'Name' : {
            return await import ('@/components/svg/IconName.vue');
        }

        case 'Password' : {
            return await import ('@/components/svg/IconPassword.vue');
        }

        case 'Email' : {
            return await import ('@/components/svg/IconEmail.vue');
        }

        case 'Cross' : {
            return await import ('@/components/svg/IconCross.vue');
        }

        case 'Gender' : {
            return await import ('@/components/svg/IconGender.vue');
        }

        case 'Validation' : {
            return await import ('@/components/svg/IconValidation.vue');
        }

        case 'ArrowUp' : {
            return await import ('@/components/svg/IconArrowUp.vue');
        }

        case 'ArrowDown' : {
            return await import ('@/components/svg/IconArrowDown.vue');
        }

        default : {
            return '';
        }
    }
}