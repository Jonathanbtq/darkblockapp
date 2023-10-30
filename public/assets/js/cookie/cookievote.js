const oui = document.querySelector('.cookie_btn_oui');
const non = document.querySelector('.cookie_btn_non');

const cookieDiv = document.querySelector('.cookie');

let reponseUtilisateur;

oui.addEventListener('click', () => {
    reponseUtilisateur = "Oui";
    createCookie();
})

non.addEventListener('click', () => {
    reponseUtilisateur = "Non";
    createCookie();
})

function createCookie() {
    // Créez un cookie pour stocker la réponse
    document.cookie = "reponse=" + reponseUtilisateur;

    // Vous pouvez également ajouter une date d'expiration (par exemple, un mois à partir de maintenant)
    const expiration = new Date();
    expiration.setMonth(expiration.getMonth() + 1);
    document.cookie = "reponse=" + reponseUtilisateur + "; expires=" + expiration.toUTCString();
    cookieDiv.style.display = 'none';
}

// Lire le cookie
let reponseUser = document.cookie.split("; ").find(row => row.startsWith("reponse="));
if (reponseUser) {
    reponseUser = reponseUser.split("=")[1];
    if(reponseUser == 'Oui' || reponseUser == 'Non'){
        cookieDiv.style.display = 'none';
    }
}