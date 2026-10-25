


document.querySelectorAll(".dropdown-menu .dropdown-item")[1].addEventListener("click", function() {
    

    localStorage.setItem("handleForSelectedAttribute", "option.previous-month");
    localStorage.setItem('selectedPeriod', 'poprzedni miesiac');
    window.location.href = 'balance.html';
  
    // document.querySelector(".selected-period").innerHTML = "poprzedni miesiac";
  
    

    // document.querySelector("option.previous-month").toggleAttribute("selected");

    

});

document.querySelectorAll(".dropdown-menu .dropdown-item")[0].addEventListener("click", function() {
    

    localStorage.setItem("handleForSelectedAttribute", "option.current-month");
    localStorage.setItem('selectedPeriod', 'obecny miesiac');
    window.location.href = 'balance.html';
  
    // document.querySelector(".selected-period").innerHTML = "poprzedni miesiac";
  
    

    // document.querySelector("option.previous-month").toggleAttribute("selected");

    

});

document.querySelectorAll(".dropdown-menu .dropdown-item")[2].addEventListener("click", function() {
    // alert("luj");

    localStorage.setItem("handleForSelectedAttribute", "option.current-year");
    localStorage.setItem('selectedPeriod', 'obecny rok');
    window.location.href = 'balance.html';
  
    // document.querySelector(".selected-period").innerHTML = "poprzedni miesiac";
  
    

    // document.querySelector("option.previous-month").toggleAttribute("selected");

    

});

document.querySelectorAll(".dropdown-menu .dropdown-item")[3].addEventListener("click", function() {
   

    localStorage.setItem("handleForSelectedAttribute", "option.non-standard");
    localStorage.setItem('selectedPeriod', 'niestandardowy');
    window.location.href = 'balance.html';
  
    // document.querySelector(".selected-period").innerHTML = "poprzedni miesiac";
  
    

    // document.querySelector("option.previous-month").toggleAttribute("selected");

    

});