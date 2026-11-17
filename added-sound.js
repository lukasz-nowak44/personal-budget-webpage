const transactionAdded = new Audio("./assets/cash.mp3");

document.querySelector("form").addEventListener("submit", function(event) {

  event.preventDefault();
  transactionAdded.play();

  transactionAdded.onended = function() {
    event.target.submit();
  };
    
});

