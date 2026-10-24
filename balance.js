const modalWithDates = new bootstrap.Modal(document.getElementById ("modalWithDates"), {
  keyboard: false
});

const xValues = [];
const yValues = [];
const barColors = ["red", "green","blue","orange","brown", "yellow", "black", "purple", "pink", "violet", "beige", "lime", "gold", "gray", "lightblue", "firebrick", "khaki", "lavender", "steelblue"];

const numberOfElement = document.querySelectorAll(".category").length;

for (let i=0; i < numberOfElement; i++){

  // if (numberOfElement >= 13) {

  //   let xTruncated = document.querySelectorAll(".category")[i].innerHTML.slice(0, 5);
  //   xValues.push(xTruncated);

  // } else {

    xValues.push(document.querySelectorAll(".category")[i].innerHTML);
  // }

  yValues.push(document.querySelectorAll(".percent")[i].innerHTML);

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
            size: 10
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

let periods = document.querySelector("#periods");

let balanceResult = document.querySelector(".result").innerHTML;

periods.addEventListener("change", function (event) {

  let selectedPeriod = event.target.value;

  document.querySelector(".selected-period").innerHTML = selectedPeriod;

  if (selectedPeriod === "non-standard") {

    modalWithDates.show();

    printRangeOfDates()
  
  }
 
});

if (balanceResult >= 0) {

  document.querySelector(".deficit").classList.add("visually-hidden");
  document.querySelector(".deficit").setAttribute("aria-hidden", "true");
  document.querySelector(".verdict").style.backgroundColor = "var(--accent)";

} else {

  document.querySelector(".surplus").classList.add("visually-hidden");
  document.querySelector(".surplus").setAttribute("aria-hidden", "true");
  document.querySelector(".verdict").style.backgroundColor = "var(--button)";
}

function printRangeOfDates() {

  let myModalEl = document.getElementById('modalWithDates');

  myModalEl.addEventListener("hide.bs.modal", function (event) {

    let startDate = document.querySelector("#start-date").value;
    let endDate = document.querySelector("#end-date").value;

    document.querySelector(".selected-period").innerHTML = "from " +    startDate + " to " + endDate;

  });

}