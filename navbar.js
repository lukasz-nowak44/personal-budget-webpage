const periodsCollection = document.querySelectorAll(".dropdown-menu .dropdown-item");

const selectorsCollection = ["option.current-month", "option.previous-month", "option.current-year", "option.non-standard"];

for (let i=0; i < periodsCollection.length; i++) {

  periodsCollection[i].addEventListener("click", function() {

    let nameOfPeriod = periodsCollection[i].innerHTML;
    
    localStorage.setItem("handleForSelectedAttribute", selectorsCollection[i]);
    localStorage.setItem('selectedPeriod', nameOfPeriod);
    
  });

}

if (!(document.getElementById('date') === null)) {

  document.getElementById('date').valueAsDate = new Date();

}