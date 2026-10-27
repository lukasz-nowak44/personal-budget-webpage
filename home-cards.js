const periodsCollectionForModal = document.querySelectorAll(".periods .dropdown-item");

const cardCollection = document.querySelectorAll(".card");
const hoverCard = new Audio("./assets/hover.wav");

for (let i=0; i < periodsCollectionForModal.length; i++) {

  periodsCollectionForModal[i].addEventListener("click", function() {

    let nameOfPeriod = periodsCollectionForModal[i].innerHTML;
    
    localStorage.setItem("handleForSelectedAttribute", selectorsCollection[i]);
    localStorage.setItem('selectedPeriod', nameOfPeriod);
    
  });

}

document.querySelector(".card.current-month").addEventListener("click", function() {
    
    let nameOfPeriod = periodsCollectionForModal[0].innerHTML;

    localStorage.setItem("handleForSelectedAttribute", selectorsCollection[0]);
    localStorage.setItem('selectedPeriod', nameOfPeriod);
    
});

for (let card of cardCollection) {

  card.addEventListener ("mouseover", function () {

    hoverCard.play();

  });
  
}