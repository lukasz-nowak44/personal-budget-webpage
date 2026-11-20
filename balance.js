const modalWithDates = new bootstrap.Modal(document.getElementById ("modalWithDates"), {
  keyboard: false
});

const isModalVisible = localStorage.getItem("isModalVisible");
const period = localStorage.getItem("selectedPeriod");
const handle = localStorage.getItem("handleForSelectedAttribute");

const barColors = ["red", "green","blue","orange","brown", "yellow", "lavender", "purple"];

const numberOfElement = document.querySelectorAll(".category-expense").length;
let fontLegend = 12;

document.getElementById("start-date").value = "2020-01-01";
document.getElementById("end-date").valueAsDate = new Date();

const categories = [...document.querySelectorAll(".category-expense")].map(el => el.textContent);
const percents   = [...document.querySelectorAll(".percent")].map(el => Number(el.textContent));

const TOP = 6;
const xValues = categories.slice(0, TOP);
const yValues = percents.slice(0, TOP);

if (categories.length > TOP) {
    xValues.push("other");
    yValues.push(percents.slice(TOP).reduce((sum, p) => sum + p, 0));
}

let periods = document.querySelector("#periods");
const balanceResult = Number(document.querySelector(".result").textContent);

const optionsCollection = document.querySelectorAll("option");


for (let option of optionsCollection){

  let nameOfPeriod = option.innerHTML;
  
  if (option.hasAttribute("selected")){
    
    document.querySelector(".selected-period").innerHTML = nameOfPeriod;

    if (option.getAttribute("class") === "non-standard") {

      if (!isModalVisible) {
        modalWithDates.show();
        localStorage.setItem("isModalVisible", "yes");
      } else {
        localStorage.removeItem("isModalVisible");
      }

    } 

  }  
}

if (document.querySelector(".change-range")) {

  document.querySelector(".change-range").addEventListener("click", function () {

        modalWithDates.show();
        localStorage.setItem("isModalVisible", "yes");  

  });

}

if (balanceResult >= 0) {

  document.querySelector(".deficit").classList.add("visually-hidden");
  document.querySelector(".verdict").style.backgroundColor = "var(--accent)";

} else {

  document.querySelector(".surplus").classList.add("visually-hidden");
  document.querySelector(".verdict").style.backgroundColor = "var(--button)";
  
}

if (numberOfElement > 0) {

  document.querySelector(".no-data").classList.add("visually-hidden");

} else {

  document.querySelector(".no-data").classList.remove("visually-hidden");

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
        position: "top",
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
