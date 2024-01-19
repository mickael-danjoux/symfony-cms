import "./main";

document.querySelectorAll(".js-copy-html").forEach((button) => {
  button.addEventListener("click", () => {
    const target = button.getAttribute("data-target");
    const code = document.getElementById(target).innerText;
    navigator.clipboard.writeText(code);
    button.classList.add('copied')
    setTimeout(function() {
      button.classList.remove('copied')
    }, 1000);
  });
});

