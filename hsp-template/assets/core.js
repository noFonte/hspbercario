function byEl(el){
    return document.getElementById(el)
}
function allEl(el){
    return document.getElementsByClassName(el)
}

function el(el){
    return document.querySelector(el)
    
}


function events(){
    const boxMenu =  byEl("box_menu")
    const isFull = boxMenu.classList.contains("full")
    const body =  el('body')
    if(!isFull){
        body.classList.add('no-scroll')
        boxMenu.classList.add("full")
        boxMenu.classList.remove("hidden")
    }else{
        body.classList.remove('no-scroll')
        boxMenu.classList.add("hidden")
        boxMenu.classList.remove("full")
    }
}