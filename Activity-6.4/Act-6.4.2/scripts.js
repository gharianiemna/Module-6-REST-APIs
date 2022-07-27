window.onload = () => {
    //on crée la palette
    document.querySelectorAll("#palette div").forEach(element => {
    //on met les couleurs
    element.style.backgroundColor = element.dataset.color
    // on écoute le click
    element.addEventListener("click", () => { canvas.setColor(element.dataset.color)})
        })
    //on charge le canvas
    let canvas = new Paint("#feuille")
}