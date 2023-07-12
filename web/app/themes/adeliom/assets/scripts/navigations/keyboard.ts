const classes = {
    tabActive: "tab-active",
};

/*
Ajout d'une classe sur le body lorsque l'utilisateur navigue avec la touche tab
*/
document.addEventListener("keyup", (e) => {
    e.keyCode == 9 &&
        !document.body.classList.contains(classes.tabActive) &&
        document.body.classList.add(classes.tabActive);
});

document.addEventListener("click", () => {
    document.body.classList.contains(classes.tabActive) &&
        document.body.classList.remove(classes.tabActive);
});
