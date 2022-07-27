class Paint {
    // le constructeur récupere dans une variable qu'on appelle canvas la feuille
    constructor(canvas){
        this.draw = false
        // les coordonnées de la zone ou on se trouvai precedement
        this.prevX = 0 
        this.prevY = 0 
        // recuperer la feuille
        this.canvas = document.querySelector(canvas)
        //le contexte 2d du canvas
        this.ctx = this.canvas.getContext("2d")
        // couleur du trait et epaisseur
        this.ctx.strokeStyle = "black"
        this.ctx.lineWidth = 2 
        
        
        
        //si je clique 
        this.canvas.addEventListener("mousedown",(e) => {
            //je dessine
            this.draw = true
            // je stocke les coordonnées de départ (ou je viens de cliquer)
            this.prevX = (e.clientX - this.canvas.offsetLeft) * 400 / this.canvas.clientWidth
            this.prevY = (e.clientY - this.canvas.offsetTop) * 400 / this.canvas.clientHeight
        })

          //si je deplace la souris
        this.canvas.addEventListener("mousemove",(e) => {
            if(this.draw){
                //on calcule les coordoonées 
                let currX = (e.clientX - this.canvas.offsetLeft) * 400 / this.canvas.clientWidth
                let currY = (e.clientY - this.canvas.offsetTop) * 400 / this.canvas.clientHeight
               //on déssine
                this.dessine(this.prevX ,this.prevY, currX, currY )
                //on stocke les nouvelles coordonnées
                this.prevX = currX
                this.prevY = currY
            }
        }
        )
        
        //arreter de dessiner en lachant la souris
        this.canvas.addEventListener("mouseup", () => {
            this.draw = false
        }
        )

        //arreter de dessiner en sortant du cadre
        this.canvas.addEventListener("mouseout", () => {
            this.draw = false
        }
        )

}
  // changer de couleur
dessine(depX, depY, destX,destY){
   this.ctx.beginPath()
   this.ctx.moveTo(depX, depY) 
   this.ctx.lineTo(destX, destY) 
   this.ctx.closePath()
   this.ctx.stroke()
}
 setColor(color){
   this.ctx.strokeStyle = color
 }

}