export default rem => {
    return (
        rem * parseFloat(getComputedStyle(document.documentElement).fontSize)
    );
};
