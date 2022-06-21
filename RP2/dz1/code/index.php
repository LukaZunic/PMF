<?php 

class Wordle {
	protected $username, $numOfAttempts, $gameOver;
    protected $wordToGuess, $latestGuess, $difficulty;
	protected $errorMsg;
    protected $hintSelected, $bigHintSelected; 
    protected $numOfHintsUsed;
    protected $numOfBigHintsUsed;
    protected $letterStatus = array();
    protected $alphabet;

    protected $words = array(
        "5" => array("atlas", "glava", "tipka", "plava", "lopta"),
        "6" => array("laptop", "advent", "faraon", "amater", "fokus"),
        "7" => array("program","folklor","ledomat", "obojica", "oborina")
    );

    protected $guessHistory = array();

	function __construct() {
		$this->username = false;
        $this->wordToGuess = $this->words[array_rand($this->words)];
        $this->latestGuess = "";
		$this->numOfAttempts = 0;
		$this->gameOver = false;
		$this->errorMsg = false;
        $this->difficulty = false;
        $this->hint = false;
        $this->bigHint = false;
        $this->hintLetters = array();
        $this->bigHintLetters = array();
        $this->hintSelected = false;
        $this->bigHintSelected = false;
        $this->numOfHintsUsed = 0;

        $this->alphabet = range('a', 'z');
        $this->letterStatus = array_fill_keys($this->alphabet, 0);
	}

	function showUserForm() {
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
            <link rel="stylesheet" href="style.css">
			<title>Wordle - DZ 1</title>
		</head>
		<body>

            <h1>Wordle!</h1>

			<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
                <div style="margin-bottom: 10px; height: 50px;">
                    <label>Unesi svoje ime:</label><input type="text" name="username" size="20">
                </div>
    
                <label for="diff">Odaberi težinu igre: </label>
                <select name="diff" id="diff">
                    <?php
                        for ($i = 5; $i <= 7; $i++) {
                            echo "<option value='$i'>" . $i . " slova " . "</option>";
                        }
                    ?>
                </select>

				<button type="submit">Pokreni igru!</button>

			</form>

			<?php if($this->errorMsg !== false) echo '<p>Error: ' . htmlentities($this->errorMsg) . '</p>'; ?>
		</body>
		</html>
		<?php
	}


	function showGuessForm() {

        // echo "$this->wordToGuess";

		?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
            <link rel="stylesheet" href="style.css">
			<title>Wordle - DZ 1</title>
		</head>
		<body>

            <h1>Wordle!</h1>
			<p>
				Igrač: <?php echo htmlentities($this->username); ?>
                <br><br>
                Broj dosad izvršenih pokušaja: <?php echo htmlentities($this->numOfAttempts); ?>
			</p>

            <?php

                if($this->hint) { 

                    if($this->hintSelected) {
                        $randomIndex = rand(0, strlen($this->wordToGuess)-1);

                        // if(count(array_unique($this->hintLetters)) != $this->numOfHintsUsed) {
                        //     while(in_array($this->wordToGuess[$randomIndex], $this->hintLetters)) {
                        //         $randomIndex = rand(0, strlen($this->wordToGuess)-1);
                        //     }
                        // }

                        array_push($this->hintLetters, $this->wordToGuess[$randomIndex]);
                        $this->hintLetters = array_unique($this->hintLetters);
                        $this->hintSelected = false;
                    }

                    echo "<h2>Hint: </h2>";

                    echo "<table id='history'>";
                    echo "<tr style='diplay: flex;'>";

                    for($i = 0; $i < count($this->hintLetters); $i++) {
                        echo "<td>";
                        echo "<span class='yellow'>" . $this->hintLetters[$i] . "</span>";
                        echo "</td>";
                    }
                    echo "</tr>";
                    echo "</table>";
                }

                if($this->bigHint) {

                    if($this->bigHintSelected) {
                        $randomIndex = rand(0, strlen($this->wordToGuess)-1);

                        // while(in_array($this->wordToGuess[$randomIndex], $this->bigHintLetters)) {
                        //     $randomIndex = rand(0, strlen($this->wordToGuess)-1);
                        // }
    
                        array_push($this->bigHintLetters, $this->wordToGuess[$randomIndex]);
                        $this->bigHintLetters = array_unique($this->bigHintLetters);

                        $this->bigHintSelected = false;
                    }

                    echo "<h2>Veliki hint: </h2>";

                    echo "<table id='history'>";
                    for($j = 0; $j < $this->difficulty; $j++) {
                        echo "<td>";
                        if(in_array($this->wordToGuess[$j], $this->bigHintLetters)) {
                            echo "<span class='green'>" . $this->wordToGuess[$j] . "</span>";
                        } else {
                            echo "<span class='grey'></span>";
                        }
                        echo "</td>";
                    }

                    echo "</tr>";
                    echo "</table>";
                }
            ?>

            <?php
                echo "<table id='history'>";

                for($i = 0; $i < count($this->guessHistory); $i++) {
                    echo "<tr style='diplay: flex;'>";

                    for($j = 0; $j < $this->difficulty; $j++) {
                        echo "<td>";
                        if($this->guessHistory[$i][$j] == $this->wordToGuess[$j]) {
                            $this->letterStatus[$this->wordToGuess[$j]] = 3;
                            echo "<span class='green'>" . $this->guessHistory[$i][$j] . "</span>";
                        } else if(strpos($this->wordToGuess, $this->guessHistory[$i][$j]) !== false) {
                            $this->letterStatus[$this->wordToGuess[$j]] = 2;
                            echo "<span class='yellow'>" . $this->guessHistory[$i][$j] . "</span>";
                        } else {
                            echo "<span class='grey'>" . $this->guessHistory[$i][$j] . "</span>";
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            ?>

            <?php
                echo "<table id='history'>";

                    for($i = 0; $i < count($this->alphabet); $i++) {

                        echo "<td>";
                        switch(array_values($this->letterStatus)[$i]) {
                            case 0:
                                echo "<span class='lightgrey'>" . $this->alphabet[$i] . "</span>";
                                break;
                            case 1:
                                echo "<span class='grey'>" . $this->alphabet[$i] . "</span>";
                                break;
                            case 2:
                                echo "<span class='yellow'>" . $this->alphabet[$i] . "</span>";
                                break;
                            case 3:
                                echo "<span class='green'>" . $this->alphabet[$i] . "</span>";
                                break;
                            default:
                                echo "<span class='grey'>" . $this->alphabet[$i] . "</span>";
                                break;   
                        }
                        echo "</td>";
                    }
                
                echo "</table>";
            ?>

            <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">
                <input type="radio" name="inputSelection" value="hint">Hint<br>
                <input type="radio" name="inputSelection" value="bigHint">Veliki hint<br>
                <input type="radio" name="inputSelection" value="other"><input type="text" name="guess" class="guessInput" maxlength="10" >
                <br> 
                <br>
                <input type="submit" name="submit" value="Izvrši akciju!" <?php if ($this->gameOver){ ?> disabled <?php   } ?> > 
            </form>

            <?php
                if($this->gameOver) {
                    echo "<h2>Riječ pogođena!</h2>";
                    echo "<button onclick='window.location.reload(true);'><p>Nova igra</p></button>";
                }
            ?>

			<?php if($this->errorMsg !== false) echo '<p>Error: ' . htmlentities($this->errorMsg) . '</p>'; ?>
		</body>
		</html>

		<?php
	}


	function getUsername() {

		if($this->username !== false) return $this->username;

		if(isset( $_POST['username'])) {
			if(!preg_match( '/^[a-zA-Z]{3,20}$/', $_POST['username'])) {
				$this->errorMsg = 'Ime mora sadržavati između 3 i 20 slova.';
				return false;
			} else {
				$this->username = $_POST['username'];
				return $this->username;
			}
		}

		return false;
	}

    function getDifficulty() {

		if($this->difficulty !== false) return $this->difficulty;

		if(isset( $_POST['diff'])) {
            $this->difficulty = $_POST['diff'];

            $this->wordToGuess = $this->words[$this->difficulty][rand(0, count($this->words[$this->difficulty])-1)];
            return $this->difficulty;		
		}

		return false;
	}


	function handleGuess(){

        $this->errorMsg = false;

        if(isset($_POST['inputSelection'])) {
            $selectedOption = $_POST['inputSelection'];

            if($selectedOption == "hint") {
                $this->hint = true;
                $this->hintSelected = true;
                $this->numOfHintsUsed++;
                return;
            } else if($selectedOption == "bigHint") {
                $this->bigHint = true;
                $this->bigHintSelected = true;
                return;
            } else {
                $this->latestGuess = strtolower($_POST['guess']);
                $this->numOfAttempts++;

                if(strlen($this->latestGuess) != $this->difficulty) {
                    $this->errorMsg = 'Unesena riječ nije dobre duljine';
                    return;
                }

                array_push($this->guessHistory, $this->latestGuess);

                if($this->latestGuess === $this->wordToGuess) {
                    $this->gameOver = true;
                    return;
                }
               
            }
        }

		return false;
	}


	function isGameOver() {
        return $this->gameOver;
    }


	function run() {
	
		$this->errorMsg = false;

		if($this->getUsername() === false || $this->getDifficulty() === false) {
			$this->showUserForm();
			return;
		}

		$rez = $this->handleGuess();

		if($rez === true) {
			echo "Pogođena riječ";
		} else {
            $this->showGuessForm();
        }
			
	}
};


session_start();

if(!isset( $_SESSION['game'])) {
	$game = new Wordle();
	$_SESSION['game'] = $game;
} else {
	$game = $_SESSION['game'];
}

$game->run();

if($game->isGameOver()){
	session_unset();
	session_destroy();
} else {
	$_SESSION['game'] = $game;	
}
