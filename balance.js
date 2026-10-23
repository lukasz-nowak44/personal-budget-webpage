const modalWithDates = new bootstrap.Modal(document.getElementById ("modalWithDates"), {
  keyboard: false
});

let periods = document.querySelector("#periods");

periods.addEventListener("change", function (event) {

  let selectedPeriod = event.target.value;

  document.querySelector(".selected-period").innerHTML = selectedPeriod;

  if (selectedPeriod === "non-standard") {

    modalWithDates.show();

    printRangeOfDates()
  
  }
 
});

let balanceResult = document.querySelector(".result").innerHTML;

if (balanceResult >= 0) {

  document.querySelector(".deficit").classList.add("visually-hidden");

} else {

  document.querySelector(".surplus").classList.add("visually-hidden");
}

function printRangeOfDates() {

  let myModalEl = document.getElementById('modalWithDates');

  myModalEl.addEventListener("hide.bs.modal", function (event) {

    let startDate = document.querySelector("#start-date").value;
    let endDate = document.querySelector("#end-date").value;

    document.querySelector(".selected-period").innerHTML = "from " +    startDate + " to " + endDate;

  });

}