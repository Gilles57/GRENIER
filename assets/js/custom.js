window.onload = (event) => {
    console.log('La page est complètement chargée');
    console.log(screen.width);
}

let d1 = document.getElementById("bandeau");
let togg = document.getElementById("togg");
console.log(togg)
togg.addEventListener("click", () => {

    if(getComputedStyle(d1).display !== "none"){
        d1.style.display = "none";
    } else {
        d1.style.display = "block";
    }
});