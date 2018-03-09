<!DOCTYPE html>
<!--
    John Galletta
    INFO600-001
    February 24, 2018
    Dr. Yuan An
    Assignment 3 - PHP/AJAX Server-Side Functionality with Asynchronous Page Events
-->
<html>
    <head>
        <link rel="stylesheet" href="assign3.css">
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // put your code here
        // Built by LucyBot. www.lucybot.com
        $age = $_GET['age'];
        $contributor = $_GET['contributor'];
        $publisher = $_GET['publisher'];
        $isbn = $_GET['isbn'];
        $title = $_GET['title'];
        $author = $_GET['author'];
        $price = $_GET['price'];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $query = array(
            "api-key" => "25749a3be59f494ab4985cc860d1b73f",
        );

        $query['age-group'] = $age;
        $query['contributor'] = $contributor;
        $query['publisher'] = $publisher;
        if (strlen($isbn) == 10 || strlen($isbn) == 13) {
            $query['isbn'] = $isbn;
        }
        $query['title'] = $title;
        $query['author'] = $author;
        //$query['price'] = $price;

        curl_setopt($curl, CURLOPT_URL, "https://api.nytimes.com/svc/books/v3/lists/best-sellers/history.json" . "?" . http_build_query($query)
        );
        $result = json_decode(curl_exec($curl));

        $books = $result->results;

        $count = 0;

        if (count($books) != 0) {
            foreach ($books as $book) {
                if ($price == "" || $book->price <= $price) {
                    ++$count;
                }
            }
        }

        if ($count > 0) {
            echo "<h2 class='w3-blue shadowedtext' style='margin-left:15px'>Search Result: " . $count;
            echo " book";

            if ($count != 1) {
                echo "s";
            }

            echo " match";

            if ($count == 1) {
                echo "es";
            }

            echo " your criteria.</h2>";
            echo "<table class='special' style='margin-left:15px; margin-right:15px; margin-bottom:15px'>";
            echo "<tr class='special' bottom-><th>Title</th><th>Author</th><th>Description</th><th>Contributor</th><th>Publisher</th><th>ISBN Numbers</th><th>Price</th></tr>";

            foreach ($books as $book) {
                if ($price == "" || $book->price <= $price) {
                    echo "<tr class='special'>";

                    echo "<td class='special'>" . $book->title . "</td>";
                    echo "<td class='special'>" . $book->author . "</td>";
                    echo "<td class='special'>" . $book->description . "</td>";
                    echo "<td class='special'>" . $book->contributor . "</td>";
                    echo "<td class='special'>" . $book->publisher . "</td>";

                    $isbns = $book->isbns;
                    echo "<td class='special'>";
                    foreach ($isbns as $isbn) {
                        $link10 = "<a title='Search Amazon' href='https://www.amazon.com/gp/search/ref=sr_adv_b/?search-alias=stripbooks&unfiltered=1&field-isbn=" . $isbn->isbn10 . "' target='_blank'>";
                        $link13 = "<a title='Search Amazon' href='https://www.amazon.com/gp/search/ref=sr_adv_b/?search-alias=stripbooks&unfiltered=1&field-isbn=" . $isbn->isbn13 . "' target='_blank'>";
                        echo "ISBN-10: " . $link10 . $isbn->isbn10 . "</a>, ISBN-13: " . $link13 . $isbn->isbn13 . "</a><br/>";
                    }
                    echo "</td>";

                    if ($book->price != 0) {
                        echo "<td class='special'>" . "$" . $book->price . "</td>";
                    } else {
                        echo "<td class='special'>N/A</td>";
                    }
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<h2 class='w3-blue shadowedtext' style='margin-left:15px'>Your search returned no results.</h2>";
        }

        //echo json_encode($result);
        ?>
    </body>
</html>
