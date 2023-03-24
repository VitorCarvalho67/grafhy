var btn1 = document.querySelector("#btn1");
var btn2 = document.querySelector("#btn2");

var x = 1;

btn1.addEventListener ("click", () => {
    new EA();
});

btn2.addEventListener ("click", () => {
    new EA();
});

function EA(){
    if (x == 1){
        asideB.classList.add("abrir");
        asideB.classList.remove("asideB");
        asideB.classList.remove("fechar");
        btn2.style.display = "flex";
        x = 2;
    }
    else if(x == 2){
        asideB.classList.add("fechar");
        asideB.classList.remove("abrir");
        btn2.style.display = "none";
        x = 1;
    }
}