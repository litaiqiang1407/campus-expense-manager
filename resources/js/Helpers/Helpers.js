import Swal from 'sweetalert2';

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

const goPage = (router, page) => {
    router.push({ name: page });
};

const showAlert = ({ title, text, confirmText, onConfirm }) => {
    Swal.fire({
        title,
        text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00BC2A',
        cancelButtonColor: '#FF2121',
        confirmButtonText: confirmText,
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed && onConfirm) {
            onConfirm();
        }
    });
}

const goBack = (router) => {
    const currentPath = window.location.pathname; 
    const pathSegments = currentPath.split('/').filter(segment => segment); 

    if (pathSegments.length > 1) {
        pathSegments.pop(); 
        const newPath = '/' + pathSegments.join('/');
        router.push(newPath);  
    } else {
        router.push('/');
    }
};

const goCancel = (router) => {
    const currentPath = window.location.pathname; 
    let targetPath = '/'; 

    const cancelMapping = [
        { pattern: /^\/my-wallet\/edit\/\d+$/, target: '/my-wallet' },
        { pattern: /^\/transaction\/edit\/\d+$/, target: '/transaction' },
        { pattern: /^\/transaction\/transaction-details\/\d+$/, target: '/transaction' },
    ];

    for (const { pattern, target } of cancelMapping) {
        if (pattern.test(currentPath)) {
            targetPath = target;
            break;
        }
    }
    router.push(targetPath);  
};

const goSelect = (router, page) => {
    const currentPath = window.location.pathname; 
    localStorage.setItem('previousPath', currentPath); 
    router.push(`${currentPath}/${page}`); 
};

export { formatBalance, showAlert, goPage, goBack, goCancel, goSelect };
