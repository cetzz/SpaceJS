<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
</head>
<link href="bootstrap5/css/bootstrap.min.css" rel="stylesheet" >
<script src="bootstrap5/js/bootstrap.bundle.min.js" ></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<body>
    <style type="text/css">
        .row>* ,.row 
        {
            padding-right: unset;
            padding-left: unset;
            margin-top: unset;
            margin-right: unset;
            margin-left: unset;
        }
    </style>
<script>
    
    //This makes the overlay
    $("body").append('<div class="row" id="overlay" style="position: absolute;"><span class="col" style="font-size: xx-large;margin-left: 0.5em;font-weight: bold;color: antiquewhite;"id="score">aaaa</span></div>')
    //variable initialization
    p=0
    height=9
    width=35
    tickspeed=100
    stop=false    
    score=0
    difficulty=5
    var grid=[]
    //creating the grid
    for(y=0;y<=height;y++){
        grid.push([]);
        $("body").append("<div class='row' id='"+y+"'>")
        for (x=0;x<=width;x++){
            $("#"+y+"").append("<div class='col-sm' id='"+y+x+"'> </div>")
            grid[y][x]=0;
        }
    }
    cw = $('#00').width()
    for(y=0;y<=height;y++){
        for (x=0;x<=width;x++){
            $('#'+y+x).css({'height':cw+'px'})
        }
    }
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
    }
    function scoreUpdate(){
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
            $('.col-sm').css({'background-color':'black'})
            allentities=findAllEntities()
            allentities.forEach( function(entity, indice, allentities) {
                if (entity[2]==2){
                    
                    if (entity[0]+1>height){
                        grid[entity[0]][entity[1]]=0
                    }else{
                        if(grid[entity[0]+1][entity[1]]==1){
                            collision(grid[entity[0]][entity[1]])
                            grid[entity[0]][entity[1]]=0
                            playercolor='ff0000'
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
            for(y=0;y<=height;y++){
                for(x=0;x<=width;x++){
                    if (grid[y][x]!=0){
                        switch(grid[y][x]){
                            case 1:
                                document.getElementById(y+''+x).style.setProperty('background-color', "#"+playercolor, 'important');
                            break;
                            case 2:
                                document.getElementById(y+''+x).style.setProperty('background-color', "grey", 'important');
                            break;
                        }
                    }
                }
            }
            playercolor='ffffff'
        }else{
            if(p==0){
                yandx=findEntity(1)
                document.getElementById(yandx[0]+''+yandx[1]).style.setProperty('background-color', "red", 'important');
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
        stop=true
        console.log('Colision con: '+entity);
    }
    function log(log){
        $("#log").append(log);
        console.log(log);
    }
    function showLog(show=true){  
        if (show){
            document.getElementById('log').style.setProperty('display', 'flex', 'important');
        }else{
            document.getElementById('log').style.setProperty('display', 'none', 'important');
        }
    }
</script>
</body>
</html>