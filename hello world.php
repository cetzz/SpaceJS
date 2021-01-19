<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
</head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<body>
<script>
    //This makes the overlay
    //variable initialization
    p=0
    width=100 //first max: 20 , post optimization 200 runs smooth as fuck and is probably my favourite, but it does not crash even if you go higher, its just too good
    tilewidth=globalThis.outerWidth/width
    height=0
    while((height*tilewidth+tilewidth)<$(window).height()){
        height++
    }
    height--
    playercolor='ffffff'
    tickspeed=100
    stop=false    
    score=0
    difficulty=8
    console.log()
    var grid=[]
    initialbody=''
    //creating the grid
    $("body").append('<div class="row" id="overlay" style="position: absolute;"><span class="col" style="font-size: xx-large;margin-left: 0.5em;font-weight: bold;color: antiquewhite;"id="score">aaaa</span></div>')
    
    for(y=0;y<=height;y++){
        grid.push([]);
        initialbody=initialbody+("<div id='row"+y+"' style='display:flex'>")   
        for (x=0;x<=width;x++){
            initialbody=initialbody+("<div class='all' id='col"+y+'-'+x+"' style='width: "+tilewidth+"px; display:flex;height:"+tilewidth+"px;'> </div>")
            grid[y][x]=0;
        }
        initialbody=initialbody+"</div>"
    }
    $("body").append(initialbody);
    //This sets the tickspeed. Enemies and things apart from the player will act by this.
    setInterval(forcedUpdate, tickspeed);
    //This sets the score timer.
    setInterval(scoreUpdate, 100);
    //This sets the initial player position
    grid[height][Math.round(width/2)]=1;
    playercolor='ffffff'
    //1 means player
    //CONTROLS
    document.onkeydown = checkKey;
    function checkKey(e) {
        e = e || window.event;
        if (e.keyCode == '38') {
            movement('up')
        }
        else if (e.keyCode == '40') {
            movement('down')
        }
        else if (e.keyCode == '37') {
            movement('left')
        }
        else if (e.keyCode == '39') {
            movement('right')
        }
        else if (e.keyCode == '78') {
            init()
        }
    }
    async function scoreUpdate(){
        if (!stop){
            score++
            document.getElementById("score").textContent=score;
        }
    }
    //generateEnemy()
    function generateEnemy(){
        grid[0][(Math.floor(Math.random() * (width - 0 + 1)) + 0)]=2
    }
    function forcedUpdate() {
        if (!stop){
            allentities=findAllEntities()
            allentities.forEach( function(entity, indice, allentities) {
                if (entity[2]==2){    
                    if (entity[0]+1>height){
                        grid[entity[0]][entity[1]]=0
                    }else{
                        if(grid[entity[0]+1][entity[1]]==1){
                            collision(grid[entity[0]][entity[1]])
                            grid[entity[0]][entity[1]]=0
                        }else{
                            grid[entity[0]][entity[1]]=0
                            grid[entity[0]+1][entity[1]]=2
                        }
                    }
                }
            });
            for(a=0;a<difficulty;a++){
                if((Math.floor(Math.random() * (1 - 0 + 1)) + 0)==1){
                    generateEnemy()
                }
            }
            render()            
        }else{
            if(p==0){
                render()
                yandx=findEntity(1)
                console.log(yandx)
                console.table(grid);
                p++
            }
        }
    }
    function findEntity(entity){
        for(y=0;y<=height;y++){
            for(x=0;x<=width;x++){
                if (grid[y][x]==entity){
                    yandx=[y,x]
                    return yandx
                }
            }
        }
    }
    function findAllEntities(){
        entities=[]
        i=0
        for(y=0;y<=height;y++){
            for(x=0;x<=width;x++){
                if (grid[y][x]!=0){
                    entities[i]= [y,x,grid[y][x]]
                    i++
                }
            }
        }
        return entities
    }
    async function render(initial=false){
            for(y=0;y<=height;y++){
                for(x=0;x<=width;x++){
                    switch(grid[y][x]){
                        case 1:
                            document.getElementById('col'+y+'-'+x).style.setProperty('background-color', "#"+playercolor, 'important');                                
                        break;
                        case 2:
                            document.getElementById('col'+y+'-'+x).style.setProperty('background-color', "grey", 'important');
                        break;
                        default:
                            document.getElementById('col'+y+'-'+x).style.setProperty('background-color', "black", 'important');
                        break;
                    }    
                }
            }
    }
    function init(){
        for(y=0;y<=height;y++){
            for (x=0;x<=width;x++){
                grid[y][x]=0;
            }
        }
        //This sets the initial player position
        grid[height][Math.round(width/2)]=1;
        playercolor='ffffff'
        score=0
        stop=false
        p=0
    }
    function movement(direction) {
        yandx=findEntity(1)
        //log(direction);
        if(!stop){
        if (direction == 'up') {
            if (height>=yandx[0] && 0<yandx[0]){
                if(grid[yandx[0]-1][yandx[1]]!=0){
                    collision(grid[yandx[0]-1][yandx[1]])
                }else{
                    grid[yandx[0]][yandx[1]]=0
                    grid[yandx[0]-1][yandx[1]]=1
                }
            }
        }
        else if (direction == 'down') {
            if (0<=yandx[0] && height>yandx[0]){
                if(grid[yandx[0]+1][yandx[1]]!=0){
                    collision(grid[yandx[0]+1][yandx[1]])
                }else{
                    grid[yandx[0]][yandx[1]]=0
                    grid[yandx[0]+1][yandx[1]]=1
                }
            }
        }
        else if (direction == 'left') {
            if (width>=yandx[1] && 0<yandx[1]){
                if(grid[yandx[0]][yandx[1]-1]!=0){
                    collision(grid[yandx[0]][yandx[1]-1])
                }else{
                    grid[yandx[0]][yandx[1]]=0
                    grid[yandx[0]][yandx[1]-1]=1
                }
            }
        }
        else if (direction == 'right') {
            if (0<=yandx[1] && width>yandx[1]){
                if(grid[yandx[0]][yandx[1]+1]!=0){
                    collision(grid[yandx[0]][yandx[1]+1])
                }else{
                    grid[yandx[0]][yandx[1]]=0
                    grid[yandx[0]][yandx[1]+1]=1
                }
            }
        }
        }
    }
    function collision(entity){
        playercolor='ff0000'
        stop=true
    }
</script>
</body>
</html>