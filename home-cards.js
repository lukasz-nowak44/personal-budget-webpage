const periodsCollectionForModal = document.querySelectorAll(".periods .dropdown-item");

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