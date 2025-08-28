
document.querySelectorAll('.dropdown-title').forEach(title => {
    title.addEventListener('click', () => {
        const parent = title.parentElement;
        parent.classList.toggle('active');
    });
});
