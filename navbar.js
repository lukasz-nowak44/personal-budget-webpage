document.querySelectorAll(".dropdown-menu .dropdown-item")[0].addEventListener("click", function() {
    

    localStorage.setItem("handleForSelectedAttribute", "option.current-month");
    localStorage.setItem('selectedPeriod', 'obecny miesiac');
    
});

document.querySelectorAll(".dropdown-menu .dropdown-item")[1].addEventListener("click", function() {
    

    localStorage.setItem("handleForSelectedAttribute", "option.previous-month");
    localStorage.setItem('selectedPeriod', 'poprzedni miesiac');
  
});


document.querySelectorAll(".dropdown-menu .dropdown-item")[2].addEventListener("click", function() {

    localStorage.setItem("handleForSelectedAttribute", "option.current-year");
    localStorage.setItem('selectedPeriod', 'obecny rok');

});

document.querySelectorAll(".dropdown-menu .dropdown-item")[3].addEventListener("click", function() {
   
    localStorage.setItem("handleForSelectedAttribute", "option.non-standard");
    localStorage.setItem('selectedPeriod', 'non-standard');

});