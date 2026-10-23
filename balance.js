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

let balanceResult = document.querySelector(".result").innerHTML;

if (balanceResult >= 0) {

  document.querySelector(".deficit").classList.add("visually-hidden");

} else {

  document.querySelector(".surplus").classList.add("visually-hidden");
}
