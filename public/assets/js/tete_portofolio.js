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

// Slider tete

// const slider = document.querySelector(".prt_wtn_head");
// const prevButton = document.getElementById("prevButton");
// const nextButton = document.getElementById("nextButton");
// let currentIndex = 0;

// nextButton.addEventListener("click", () => {
//   if (currentIndex < slider.children.length - 1 && isNextElementHidden()) {
//     currentIndex++;
//     updateSlider();
//   }
// });

// prevButton.addEventListener("click", () => {
//   if (currentIndex > 0) {
//     currentIndex--;
//     updateSlider();
//   }
// });

// function isNextElementHidden() {
//   const nextSlide = slider.children[currentIndex + 1];
//   return nextSlide && nextSlide.offsetLeft < slider.offsetWidth;
// }

// function updateSlider() {
//   const slideWidth = slider.children[0].offsetWidth;
//   slider.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
//   console.log(currentIndex);
//   if(currentIndex === slider.children.length){
//     nextButton.disabled
//   }
//   prevButton.disabled = currentIndex === 0;
// }


const productContainers = document.querySelector('.prt_wtn_head');
const firstImg = productContainers.querySelectorAll('.prt_equipe_tete_card img')[0]
const nxtBtn = document.getElementById('nextButton');
const preBtn = document.getElementById('prevButton');

const arrowIcons = document.querySelectorAll('.prt_equipe_slider button')

const imgCard = document.querySelector('.prt_equipe_tete_card');
let totalWidth = imgCard.offsetWidth;

let isDragStart = false, prevPageX, prevScrollLeft;
let firstImgWidth = firstImg.clientWidth + 14;

arrowIcons.forEach(icon => {
    icon.addEventListener('click', () => {
        const scrollAmount = icon.id == "prevButton" ? -firstImgWidth : firstImgWidth;
        productContainers.scrollBy({
            left: scrollAmount,
            behavior: 'smooth' // Rend le défilement en douceur
        });
    });
});

const dragStart = (e) => {
    isDragStart = true;
    prevPageX = e.pageX;
    prevScrollLeft = productContainers.scrollLeft;
}
const dragging = (e) => {
    if(!isDragStart) return;
    e.preventDefault();
    let positionDiff = e.pageX - prevPageX;
    productContainers.scrollLeft = prevScrollLeft - positionDiff;
}

const dragStop = () => {
    isDragStart = false;
}
productContainers.addEventListener('mousedown', dragStart)
productContainers.addEventListener('mousemove', dragging)
productContainers.addEventListener('mouseup', dragStop)