<?php
session_start();
require_once "../objects/card.php";
require_once "../objects/player.php";
require_once "../config.php";
require_once "../glossary.php";

$player = unserialize($_SESSION["player"]);
$unlockedCards = $player->get_unlocked_cards($link);

$unlockedCardsIds = "";
foreach ($unlockedCards as $card) {
    $unlockedCardsIds .= $card->id . ",";
}
$unlockedCardsIds = substr($unlockedCardsIds, 0, -1);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Balíčky</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body class="m-5">
    <a class="btn btn-outline-secondary" href="../home.php"><i class="bi bi-arrow-left"></i></a>
    <div class="mt-5">
        <h1> Vytvořit nový balíček</h1>
        <div hidden id="dataHolder" data-unlocked-cards=<?php echo $unlockedCardsIds; ?> data-player-id=<?php echo $player->id; ?>></div>
        <div class="row">
            <div class="col-3">
                <p>Vybráno <b id="cardsSum" data-cards-sum="0">0</b>/10 karet.</p>

            </div>
            <div class="col-3">
                <input class="form-control" id="packName" placeholder="Jméno balíčku">
            </div>
            <div class="col-3">
                <a class="btn btn-success" id="savePackBtn"><i class="bi bi-check2"></i></a>
            </div>
        </div>
        <table class="table table-striped table-hover">
            <caption>Seznam odemčených karet</caption>
            <thead class="table-dark">
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Název</th>
                    <th scope="col">Vlastnosti</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($unlockedCards as $card) {
                    echo $card->renderRow($cardGlossary) . "<br>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $(".cardMinusBtn").click(function() {
                let cardId = $(this).parent().data("cardId");

                var cardCountText = $("#cardCount" + cardId);
                let currCount = cardCountText.data("cardCount");

                if (currCount > 0) {
                    currCount--;
                    cardCountText.data("cardCount", currCount);

                    var cardsSumText = $("#cardsSum");
                    let cardsSum = cardsSumText.data("cardsSum");
                    cardsSumText.data("cardsSum", cardsSum - 1);
                    cardsSumText.html(cardsSum - 1);
                }
                cardCountText.html("<b>" + currCount + "</b>/2");
            });

            $(".cardPlusBtn").click(function() {
                let cardId = $(this).parent().data("cardId");

                var cardCountText = $("#cardCount" + cardId);
                let currCount = cardCountText.data("cardCount");

                if (currCount < 2) {
                    var cardsSumText = $("#cardsSum");
                    let cardsSum = cardsSumText.data("cardsSum");

                    if (cardsSum < 10) {
                        currCount++;
                        cardCountText.data("cardCount", currCount);

                        cardsSumText.data("cardsSum", cardsSum + 1);
                        cardsSumText.html(cardsSum + 1);
                    } else {
                        alert("Maximální počet karet pro balíček dosažen!");
                    }
                }

                cardCountText.html("<b>" + currCount + "</b>/2");
            });

            $("#savePackBtn").click(function() {
                let packName = $("#packName").val();
                if (packName == "") {
                    alert("Pojmenujte balíček");
                } else {
                    let cardIds = $("#dataHolder").data("unlockedCards").split(",");

                    let selectedCards = ""
                    cardIds.forEach(cardId => {
                        let cardCount = $("#cardCount" + cardId).data("cardCount");
                        selectedCards += (cardId + ",").repeat(cardCount);
                    });
                    selectedCards = selectedCards.substring(0, selectedCards.length - 1);

                    if (selectedCards.length > 0) {
                        let playerId = $("#dataHolder").data("playerId");
                        $.ajax({
                            type: 'POST',
                            url: 'savePack.php',
                            data: {
                                name: packName,
                                cards: selectedCards,
                                player_id: playerId
                            },
                            success: function(response) {
                                alert(response);
                            },
                            error: function(xhr, status, error) {
                                console.log(xhr);
                                console.log(status);
                                console.log(error);
                            },
                            dataType: "json",
                        });
                    } else {
                        alert("Někde se stala chyba");
                    }
                }
            });
        });
    </script>
</body>

</html>