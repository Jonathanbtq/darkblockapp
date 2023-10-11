const card_tete_ctn = document.querySelectorAll('.prt_equipe_tete_card');

card_tete_ctn.forEach(div => {
    div.addEventListener('mouseover', () => {
        const card_dsc = div.querySelector('.prt_eip_card_dsc');
        if (card_dsc) {
            card_dsc.style.display = 'flex';
            card_dsc.style.opacity = '0.7';
        }
    });

    div.addEventListener('mouseout', () => {
        const card_dsc = div.querySelector('.prt_eip_card_dsc');
        if (card_dsc) {
            card_dsc.style.display = 'none';
        }
    });
})

/*********************/

const bigImgContainer = document.querySelector('.prt_ctn_bigimg');
const body = document.body;
const imgList = document.querySelectorAll('.prt_img_crd_div > img');
const imgBig = document.querySelector('.prt_figure_content > img');

console.log(imgBig);
imgList.forEach(img => {
    img.addEventListener('click', () => {
        const src = img.src;
        document.querySelector('.prt_ctn_bigimg').style.display = 'flex';
        imgBig.src = src;

        // Bloquer le scroll
        body.classList.add('no_scroll');
        const couleurFond = 'rgba(44, 43, 43, 0.80)';
        bigImgContainer.style.background = couleurFond;
    }
)})

// Ajoutez un gestionnaire d'événements pour cacher l'élément
document.querySelector('.prt_ctn_content > button').addEventListener('click', function () {
    bigImgContainer.style.display = 'none';
    body.classList.remove('no_scroll');
});
bigImgContainer.addEventListener('click', function () {
    bigImgContainer.style.display = 'none';
    body.classList.remove('no_scroll');
});