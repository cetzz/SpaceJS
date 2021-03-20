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
    width=30 //first max: 20 , post optimization 200 runs smooth as fuck and is probably my favourite, but it does not crash even if you go higher, its just too good
    var ratio = window.devicePixelRatio || 1;
    var globalw = screen.width * ratio;
    let check = false;
        (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
    console.log(check);
    tilewidth=(check?globalw:globalThis.outerWidth)/width;

    height=0
    while((height*tilewidth+tilewidth)<$(window).height()){
        height++
    }
    height--
    playercolor='ffffff'
    tickspeed=100
    stop=false    
    score=0
    difficulty=10
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
    window.addEventListener('touchstart', checkTouch);
    function checkTouch(a) {
        if(a.type == 'touchstart' || a.type == 'touchmove' || a.type == 'touchend' || a.type == 'touchcancel'){
            pointerEvent = a.targetTouches[0];
            x = pointerEvent.pageX;
            y = pointerEvent.pageY;
        }

        console.log(x+','+y);
    }
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