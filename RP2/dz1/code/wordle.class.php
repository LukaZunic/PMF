<?php
    class Wordle {

        private $words = array();
        private $difficulty;
        private $numOfAttempts;
        private $username = '';   
        private $error;
        private $errorMessage;
        private $isGameOver = false;

        public function __construct() {
            $this->numOfAttempts = 0;
            $this->error = false;
            $this->errorMsg = '';
        }

        function init() {
	
            $this->error = false;

            if($this->getUsername() == ''){
                
                $this->createUserForm();
                return;
            }

            // Dakle imamo ime igrača.
            // Ako je igrač pokušao pogoditi broj, provjerimo što se dogodilo s tim pokušajem.
            $rez = $this->handleGuess();

            // if( $rez === PogodiBroj::ZAMISLJENI_JE_ISTI ) {
            //     $this->ispisiCestitku();
            //     $this->gameOver = true;
            // } else {

            // }
                // $this->ispisiFormuZaPogadjanjeBroja( $rez );
	    }  

        
        function handleGuess() {

        }

        function getUsername() {

            if($this->username !== '') return $this->username;

            if(isset($_POST['name'])){
                if(!preg_match( '/^[a-zA-Z]{3,20}$/', $_POST['name'])) {
                    $this->errorMsg = 'Ime igrača treba imati između 3 i 20 slova.';
                    echo $this->errorMsg;
                    return false;
                } else {
                    $this->username = $_POST['name'];
                    return $this->username;
                }
            }

            return '';
        }


        function createUserForm() {
            ?>
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Wordle - DZ 1</title>
                </head>
                <body>
                    <form id="user_data_form" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">
                        <label for="name">Unesi svoje ime:  </label>
                        <input type="text" id="name" name="name"><br>
                        <br>

                        <label for="diff">Odaberi težinu igre: </label>
                        <select name="diff" id="diff">
                            <?php
                                for ($i = 5; $i <= 9; $i++) {
                                    echo "<option value='$i'>" . $i . " slova " . "</option>";
                                }
                            ?>
                        </select>

                        <input type="submit" value="Pokreni igru">
                    </form>

                    <?php if($this->error !== false) echo '<p>Greška: ' . htmlentities($this->errorMsg) . '</p>'; ?>
                </body>
                </html>
            <?php
        }

        function isGameOver(){
            return $this->isGameOver;
        }

    }

    session_start();

    if(!isset($_SESSION['game'])) {
        $game = new Wordle();
        $_SESSION['game'] = $game;
    } else {
        $game = $_SESSION['game'];
    }

    $game->init();

    if($game->isGameOver()) {
        session_unset();
        session_destroy();
    } else {
        $_SESSION['game'] = $game;	
    }

?>