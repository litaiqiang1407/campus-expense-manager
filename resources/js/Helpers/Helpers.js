const formatBalance = (balance, useAbbreviation = false, includeCurrencySymbol = true) => {
    if (balance === 0) return includeCurrencySymbol ? '$0' : '0';

    const abbreviations = [
        { value: 1e9, symbol: 'B' }, 
        { value: 1e6, symbol: 'M' }, 
        { value: 1e3, symbol: 'K' }  
    ];

    const absBalance = Math.abs(balance);
    let formattedBalance;

    if (useAbbreviation) {
        const abbreviation = abbreviations.find(abbr => absBalance >= abbr.value);
        if (abbreviation) {
            formattedBalance = (absBalance / abbreviation.value).toFixed(2).replace(/\.00$/, '') + abbreviation.symbol;
        } else {
            formattedBalance = Number.isInteger(absBalance) ? absBalance : absBalance.toFixed(2);
        }
    } else {
        formattedBalance = Number.isInteger(absBalance) ? absBalance : absBalance.toFixed(2);
    }

    return includeCurrencySymbol 
        ? `${balance < 0 ? '-$' : '$'}${formattedBalance}`
        : `${balance < 0 ? '-' : ''}${formattedBalance}`;
};

export { formatBalance };
