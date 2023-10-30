reponseUser = document.cookie.split("; ").find(row => row.startsWith("reponse="));
if (reponseUser) {
    reponseUser = reponseUser.split("=")[1];
    if(reponseUser == 'Oui'){
        // Récupérez le tableau JSON du cookie (s'il existe)
        let voteCookie = document.cookie.split(';').find(row => row.trim().startsWith('votes='));
        let votes = [];

        if (voteCookie) {
            votes = JSON.parse(voteCookie.split('=')[1]);
        }

        // Supposons que vous ayez des boutons de vote avec la classe "btn_js_coo" et un attribut "data-idvote" pour stocker l'ID du vote
        const btnVote = document.querySelectorAll('.btn_js_coo');

        // Maintenant, "votes" est un tableau d'objets contenant les réponses et les IDs des votes associés
        // Vous pouvez accéder aux données de chaque réponse en parcourant le tableau "votes"
        votes.forEach(voteData => {
            let idVote = voteData.idvote;
            let response = voteData.response;
            btnVote.forEach(btn => {
                if(btn.getAttribute('data-idvote') === idVote){
                    if(btn.innerHTML === response){
                        btn.innerHTML = response + `<i class="fa-solid fa-check"></i>`;
                    }
                }
            });
        });


        if (voteCookie) {
            votes = JSON.parse(voteCookie.split('=')[1]);
        }

        btnVote.forEach(btn => {
            btn.addEventListener('click', () => {
                let voteTxt = btn.textContent;
                let idVote = btn.getAttribute('data-idvote'); // Récupérez l'ID du vote depuis un attribut

                // Recherchez si l'utilisateur a déjà voté pour ce vote
                const userVoteIndex = votes.findIndex(voteData => voteData.idvote === idVote);

                if (userVoteIndex !== -1) {
                    // L'utilisateur a déjà voté pour ce vote
                    // Mettez à jour la réponse existante
                    votes[userVoteIndex].response = voteTxt;
                } else {
                    // L'utilisateur n'a pas encore voté pour ce vote, ajoutez la réponse au tableau JSON
                    votes.push({ idvote: idVote, response: voteTxt });
                }

                // Mettez à jour le cookie avec le tableau JSON
                document.cookie = `votes=${JSON.stringify(votes)}`;

                // Vous pouvez également ajouter une date d'expiration au cookie si nécessaire
                const expiration = new Date();
                expiration.setMonth(expiration.getMonth() + 1);
                document.cookie = `votes=${JSON.stringify(votes)}; expires=${expiration.toUTCString()}`;
            });
        });
    }
}
