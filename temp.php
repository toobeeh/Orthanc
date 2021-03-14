<head>
<meta name="twitter:card" content="summary_large_image" />
<meta name="theme-color" content="#36aac7">
<meta content="Click the link to use this theme in skribbl.io" property="og:site_name">
<?php 
ini_set('error_reporting', E_ALL);
$_db = new SQlite3('/home/pi/Database/palantir.db');
$_db->busyTimeout(1000);
$_db->exec('PRAGMA journal_mode = wal;');
$_sql = $_db->prepare('SELECT * FROM Themes WHERE Ticket like ?');
$_sql->bindParam(1, $_GET['ticket']);
$_result = $_sql->execute();
$_row = $_result->fetchArray();
$_db->close();

echo "<title> Ticket " . $_row['Name'] . "</title>";
echo '<meta property="og:title" content="' . $_row['Name'] . '" />';
echo '<meta property="og:image" content="' . $_row['ThumbnailLanding'] . '" />';
echo '<meta property="og:description" content="' . $_row['Author'] . ' created a skribbl.io theme: ';
?>

<?php 
    echo $_row['Description'] . '" />';
?>
</head>
<body>
<?php
    echo '<pre>'; print_r($_row); echo '</pre>';
?>
</body>