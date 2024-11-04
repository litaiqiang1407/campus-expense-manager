const formatBalance = (balance) => balance === 0 ? '$0' : `${balance < 0 ? '-$' : '$'}${Math.abs(balance).toFixed(2)}`;

export { formatBalance };