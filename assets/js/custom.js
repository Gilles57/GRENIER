window.onload = (event) => {
    console.log('La page est complètement chargée');
    console.log(screen.width);
}

window.addEventListener('load', function () {
    const img = $(".slidable")
    img.hide();
    img.slideUp(0);
    img.slideDown(1000);
});

window.addEventListener('load', function () {
    const img = $(".effet")
    img.hide();
    img.slideUp(0);
    img.slideDown(2000);
});

const items = document.getElementsByClassName("survol");

for (const item of items) {
    item.addEventListener("mouseover", function () {
        this.style.cursor = 'pointer';
    })
}

for (const item of items) {
    item.addEventListener("click", function () {
        const path = item.dataset.lien;
        window.location.href = path;
    });
}

$("#myModalBtn").click(function () {
    $('#myModal').modal('show')
});



