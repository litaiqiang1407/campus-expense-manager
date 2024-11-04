const formatBalance = (balance) => {
    if (balance === 0) return '$0';
    const formattedBalance = Number.isInteger(Math.abs(balance)) 
        ? Math.abs(balance) 
        : Math.abs(balance).toFixed(2);
    return `${balance < 0 ? '-$' : '$'}${formattedBalance}`;
};

export { formatBalance };
