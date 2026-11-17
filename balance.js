const modalWithDates = new bootstrap.Modal(document.getElementById ("modalWithDates"), {
  keyboard: false
});

const isModalVisible = localStorage.getItem("isModalVisible");
const period = localStorage.getItem("selectedPeriod");
const handle = localStorage.getItem("handleForSelectedAttribute");
const xValues = [];
const yValues = [];
const barColors = ["red", "green","blue","orange","brown", "yellow", "lavender", "purple"];

const numberOfElement = document.querySelectorAll(".category-expense").length;
let fontLegend = 12;

// if (period) {

//     document.querySelector(".selected-period").innerHTML = period;

//     // if (period === "non-standard"){

//     //   printRangeOfDates();

//     // }
//     localStorage.removeItem("selectedPeriod");
// }

// if (handle) {

//   document.querySelector(handle).toggleAttribute("selected");
//   localStorage.removeItem("handleForSelectedAttribute");

// }



// localStorage.clear();

document.getElementById("start-date").value = "2020-01-01";
document.getElementById("end-date").valueAsDate = new Date();

if (numberOfElement <= 6) {

  for (let i=0; i < numberOfElement; i++){
    
    xValues.push(document.querySelectorAll(".category-expense")[i].innerHTML);
    yValues.push(document.querySelectorAll(".percent")[i].innerHTML);
    
  }

} else {

  for (let i=0; i <=6; i++){
    
    xValues.push(document.querySelectorAll(".category-expense")[i].innerHTML);
    yValues.push(document.querySelectorAll(".percent")[i].innerHTML);
    
  }

  let sumOfOther = 0;
  xValues.push("other");

  for (let i=6; i < numberOfElement; i++) {

    sumOfOther += Number(document.querySelectorAll(".percent")[i].innerHTML);

  }

  yValues.push(sumOfOther);

}

let periods = document.querySelector("#periods");
let balanceResult = document.querySelector(".result").innerHTML;

// periods.addEventListener("change", function (event) {

//   let selectedPeriod = event.target.value;

//   document.querySelector(".selected-period").innerHTML = selectedPeriod;

//   if (selectedPeriod === "non-standard") {

//     modalWithDates.show();

//     printRangeOfDates();
  
//   }
 
// });


const optionsCollection = document.querySelectorAll("option");


for (let option of optionsCollection){

  let nameOfPeriod = option.innerHTML;
  
  if (option.hasAttribute("selected")){
    
    document.querySelector(".selected-period").innerHTML = nameOfPeriod;

    if (option.getAttribute("class") === "non-standard") {

      // document.querySelector('select').setAttribute("onchange", "");

      if (!isModalVisible) {
        modalWithDates.show();
        localStorage.setItem("isModalVisible", "yes");
      } else {
        localStorage.removeItem("isModalVisible");
      }
      
      
      // printRangeOfDates();

    } 

      // document.querySelector('select').setAttribute("onchange", "this.form.submit()");

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

// function printRangeOfDates() {

//   let myModalEl = document.getElementById("modalWithDates"); 

//   myModalEl.addEventListener("hide.bs.modal", function (event) {

//     let startDate = document.querySelector("#start-date").value;
//     let endDate = document.querySelector("#end-date").value;

//     document.querySelector(".selected-period").innerHTML = "from " +    startDate + " to " + endDate;

//   });


// }


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
