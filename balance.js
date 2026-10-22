const modalWithDates = new bootstrap.Modal(document.getElementById ("modalWithDates"), {
  keyboard: false
});

let periods = document.querySelector("#periods");

periods.addEventListener("change", function (event) {

  let selectedPeriod = event.target.value;

  if (selectedPeriod === "non-standard") {

      modalWithDates.show();
  }
 
});

