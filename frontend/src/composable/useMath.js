export function formatStringToFloat(input) {
    const formattedInput = input.replace(',', '.');
    return parseFloat(formattedInput);
}

export function formatFloatAsString(number) {
    if (Math.floor(number) !== number) {
        return number.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    return number.toLocaleString('fr-FR');
}