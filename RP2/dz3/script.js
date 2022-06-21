console.log(puzzle);

var boardMatrix = [];


function startGame() {
    var select = document.getElementById('difficulty');
    var difficulty = select.options[select.selectedIndex].value;
    console.log(difficulty); 

    let board = puzzle.find(
        (b) => b.name.toLowerCase() === difficulty
    );


    for (var i = 0; i < board.size; i++) {
        boardMatrix[i] = [];
        for(j = 0; j < board.size; j++) {
            boardMatrix[i][j] = 'X';
        }
    }

    console.log(board);

    var lastClicked;
    var grid = gameTable(board.size, board.size, board, (el, row, col, i, click) => {

        if(el.innerHTML != '*') {
            if(click === 'leftclick' && el.className != 'clicked') {
                el.className = 'clicked';
    
                for(var c in board.color) {
                    if(document.getElementById(board.color[c]).checked) {
                        el.style.backgroundColor = board.color[c];
                        boardMatrix[row-1][col-1] = board.color[c];
                    }
                }

            } else if(click === 'rightclick') {
                el.className = '';
                el.style.backgroundColor = 'lightgray';
                boardMatrix[row-1][col-1] = 'X';
            }
    
        }

        checkSolution(board);

        lastClicked = el;
    });

    document.body.appendChild(grid);

    var colorMenu = document.getElementById('colorMenu');  
    document.getElementById('menuTitle').innerHTML = 'Odaberi boju za igranje: ';
  
    board.color.forEach((color, index) => {
            colorMenu.appendChild(createColorSelection('colorSelection', color, color.charAt(0).toUpperCase() + color.slice(1)));
        }
    );

    document.getElementById(board.color[0]).checked = true;
}


function checkSolution(board) {

    var colorPaths = [];
    for(let i = 0; i < board.color.length; i++) {
        colorPaths[i] 
    }

    for(var i = 0; i < boardMatrix.length; i++) {
        for(var j = 0; j < boardMatrix[0].length; j++) {
            if(boardMatrix[i][j].includes('start')) {
                var start = [i, j];
                var color = boardMatrix[i][j].split(' ')[0];
                console.log(color);

                if (!isPath(boardMatrix, boardMatrix.length, color, board)) {
                    return false;
                }

            }
        }
    }
    document.getElementById('solution').innerHTML = 'Rješenje nađeno';
    return true;
}

function isPath(matrix, n, color, board) {
     
        let visited = new Array(n);
            for(let i = 0; i<n; i++) {
                visited[i] = new Array(n);
                for(let j = 0; j<n; j++) {
                    visited[i][j]=false;
                }
            }
  
        let flag = false;
  
        for (let i = 0; i < n; i++) {
            for (let j = 0; j < n; j++) {

                if ((matrix[i][j] == color + ' start' || matrix[i][j] == color) && !visited[i][j]) {
    
                    if (checkPath(matrix, i, j, visited, color, board)) {
                        flag = true;
                        break;
                    }
                }       
            }
        }
        if (flag) {
            console.log('path exists');
            return true;
        } else {
            console.log('path does not exist');
            return false;
        }
           
}

function isReachable(i,j,matrix){
    if (i >= 0 && i < matrix.length && j >= 0 && j < matrix[0].length)
        return true;
    return false;
}

function checkPath(matrix, i, j, visited, color, board) {
        var colors = board.color
        remainingColors = colors.slice(board.color.indexOf(color));
        if (isReachable(i, j, matrix)
            && matrix[i][j] != 'X'
            && !remainingColors.includes(matrix[i][j])
            && !visited[i][j]) {
          
            visited[i][j] = true;
 
            if (matrix[i][j] == color + ' end')
                return true;
  
            let up = checkPath(matrix, i - 1, j, visited, color, board);
            if (up) return true;
  
            let left = checkPath(matrix, i, j - 1, visited, color, board);
            if(left) return true;

            let down = checkPath(matrix, i + 1, j, visited, color, board);
            if (down) return true;

            let right = checkPath(matrix, i, j + 1, visited, color, board);
            if (right) return true;
        }

        return false;
}


function createColorSelection(name, value, text) {
    var label = document.createElement("label");
    var radio = document.createElement("input");
    radio.type = "radio";
    radio.name = name;
    radio.value = value;
    radio.id = value;

    label.appendChild(radio);
    label.appendChild(document.createTextNode(text));

    return label;
}

function gameTable(rows, cols, board, callback){
    var i = 1;
    var grid = document.createElement('table');
    grid.className = 'grid';
    grid.addEventListener('contextmenu', event => event.preventDefault());

    for (var r = 1; r <= rows; r++){
        var tr = grid.appendChild(document.createElement('tr'));

        for (var c = 1; c <= cols; c++){
            var cell = tr.appendChild(document.createElement('td'));

            board.A_col.forEach((row, index) => {
                
                    const A_col = board.A_col[index];
                    const A_row = board.A_row[index];

                    const B_row = board.B_row[index];
                    const B_col = board.B_col[index];
        
                    if(c === A_row && r === A_col) {
                        cell.style.backgroundColor = board.color[index];
                        cell.innerHTML = '*';
                        boardMatrix[r-1][c-1] = board.color[index] + ' start';
                    }

                    if(c === B_row && r === B_col) {
                        cell.style.backgroundColor = board.color[index];
                        cell.innerHTML = '*';
                        boardMatrix[r-1][c-1] = board.color[index] + ' end';
                    }
                
                }
            );

            cell.addEventListener('contextmenu',(function(el,r,c,i,click){
                return function(){
                    callback(el,r,c,i,'rightclick');
                }
            })(cell,r,c,i),false);

            cell.addEventListener('click',(function(el,r,c,i,click){
                return function(){
                    callback(el,r,c,i,'leftclick');
                }
            })(cell,r,c,i),false);
        }
    }
    return grid;
}