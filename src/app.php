<?php

use Dcampos\Generador\model\Generator;

$readme = null;

if(count($_POST) > 0){
    $readme = new Generator($_POST);
    $readme->generate();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador markdown</title>
    <link rel="stylesheet" href="src/resources/main.css">
</head>
<body>
    <form action="" method="POST">
        <details>
            <summary>Title</summary>
            <div>
            <?php#Construcción de metodo estatico que valida si existe el objeto y lo retorna?>
                <input type="text" name="title" value="<?php echo Generator::getValue($readme, 'getTitle') ?>">
            </div>
        </details>

        <details>
            <summary>Description</summary>
            <div>
                <input type="text" name="description" value="<?php echo Generator::getValue($readme, 'getDescription') ?>">
            </div>
        </details>

        <details>
            <summary>Authors</summary>
                <div>
                    <?php
                        $authors = Generator::getValue($readme, 'getAuthors');

                        if(is_array($authors)){
                            foreach($authors as $author){
                    ?>
                        <div class="author">
                            <input type="text" name="authors[]" value="<?php echo $author['author'] ?>">
                            <input type="url" name="author_links[]" value="<?php echo $author['link'] ?>">
                        </div>
                    <?php
                            }
                        }else{
                    ?>
                        <div>
                            <input type="text" name="authors[]" value="">
                            <input type="url" name="author_links[]" value="">
                        </div>
                    <?php
                        }
                        ?>
                        <div id="moreAuthors"></div>
                        <button id="bAddAuthor">Add author</button>
                </div>
        </details>

        <input type="submit" value="Generate Markdown">
    </form>
    <div class="markdown">
        <pre><code><?php
            if(isset($readme)){
                echo $readme->getMarkdown();
            }
        ?></code></pre>
    </div>

    <div class="preview">
        <?php
            if(isset($readme)){
                echo $readme->getHTML();
            }
        ?>
    </div>
    <script>
        const bAddAuthor = document.querySelector('#bAddAuthor');

        bAddAuthor.addEventListener('click', e => {
            e.preventDefault();

            const authorDiv = document.createElement('div');
            authorDiv.classList.add('author');

            const authorInput = document.createElement('input');
            authorInput.name = 'authors[]';

            const linkInput = document.createElement('input');
            linkInput.name = 'author_links[]';
            linkInput.type = 'url';

            authorDiv.appendChild(authorInput);
            authorDiv.appendChild(linkInput);
            document.querySelector('#moreAuthors').appendChild(authorDiv);
        });
    </script>
</body>
</html>