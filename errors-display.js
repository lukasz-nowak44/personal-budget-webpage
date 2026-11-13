let errorsDescriptions = document.querySelectorAll("p.error");

for (i=0; i<errorsDescriptions.length; i++) {

  let nameOfId = errorsDescriptions[i].getAttribute("class").slice(6);

  document.querySelector("input#"+nameOfId).setAttribute("aria-invalid","true");

}