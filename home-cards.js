document.querySelector(".card.current-month").addEventListener("click", function() {
    
    localStorage.setItem("handleForSelectedAttribute", "option.current-month");
    localStorage.setItem('selectedPeriod', 'obecny miesiac');
    
});