const votebtn = document.querySelectorAll('.vote_choix');

votebtn.forEach(btn => {
    let pourcentage = btn.getAttribute('data');

    // Assurez-vous que le pourcentage est un nombre
    if (!isNaN(pourcentage)) {
        // Définissez la largeur de la div parente en fonction du pourcentage
        btn.style.width = pourcentage + '%';
    }
});