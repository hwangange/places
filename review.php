<!DOCTYPE html>
<html>
<head>
  <title>Birdee</title>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Biryani:400,800,900|Nunito:400,700' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>
  <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  <link href="css/extra.css" rel="stylesheet" media="screen">
  <style>
  html, body {
    height: 100%;
    padding: 0;
    margin: 0;

  }

  body {
    background: #accbe8 url("img/half-tree.png") no-repeat fixed right;
  }
</style>
<html>
  <body>
    The location received a <?php echo $_POST["rating"]; ?> out of 5.<br>
    Review: <?php echo $_POST["review"]; ?>

    <footer class = "footer" style = "background: #6ab47b">
      <div class = "container">
    </footer>
  </body>
</html>
