const modalWithDates = new bootstrap.Modal(document.getElementById ("modalWithDates"), {
  keyboard: false
});

const period = localStorage.getItem("selectedPeriod");
const handle = localStorage.getItem("handleForSelectedAttribute");
const xValues = [];
const yValues = [];
const barColors = ["red", "green","blue","orange","brown", "yellow", "lavender"];

const numberOfElement = document.querySelectorAll(".category").length;
let fontLegend = 12;

if (period) {

    document.querySelector(".selected-period").innerHTML = period;

    if (period === "non-standard"){

      modalWithDates.show();
      printRangeOfDates();
      
    }
    
}

if (handle) {

  document.querySelector(handle).toggleAttribute("selected");

}

document.getElementById("start-date").value = "2000-01-01";
document.getElementById("end-date").valueAsDate = new Date();

for (let i=0; i < 6; i++){

  xValues.push(document.querySelectorAll(".category")[i].innerHTML);
  yValues.push(document.querySelectorAll(".percent")[i].innerHTML);

}

if (numberOfElement > 6) {

  let sumOfOther = 0;
  xValues.push("other");

  for (let i=6; i < numberOfElement; i++) {

    sumOfOther += Number(document.querySelectorAll(".percent")[i].innerHTML);

  }

  yValues.push(sumOfOther);

}

let periods = document.querySelector("#periods");
let balanceResult = document.querySelector(".result").innerHTML;

periods.addEventListener("change", function (event) {

  let selectedPeriod = event.target.value;

  document.querySelector(".selected-period").innerHTML = selectedPeriod;

  if (selectedPeriod === "non-standard") {

    modalWithDates.show();

    printRangeOfDates();
  
  }
 
});

if (balanceResult >= 0) {

  document.querySelector(".deficit").classList.add("visually-hidden");
  document.querySelector(".verdict").style.backgroundColor = "var(--accent)";

} else {

  document.querySelector(".surplus").classList.add("visually-hidden");
  document.querySelector(".verdict").style.backgroundColor = "var(--button)";
  
}

function printRangeOfDates() {

  let myModalEl = document.getElementById("modalWithDates");

  myModalEl.addEventListener("hide.bs.modal", function (event) {

    let startDate = document.querySelector("#start-date").value;
    let endDate = document.querySelector("#end-date").value;

    document.querySelector(".selected-period").innerHTML = "from " +    startDate + " to " + endDate;

  });

}

new Chart("expensesChart", {
  type: "pie",
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
    
  },
  options: {
    
    plugins: {
      legend: {
        position: "bottom",
        labels: {
          font: {
            size: fontLegend
          } 
        }
      }
    },

    title: {
      display: true,
      text: "My Expenses"
    }
  }
});
