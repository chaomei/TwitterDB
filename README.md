# TwitterDB
Various examples of using PHP and MySQL with data APIs such as Twitter API.<br>

This is a PHP application with an XAMPP set up, i.e. an Apache Web Server and a MySQL database server.<br>
Here are some simple but error-prone steps to follow:<br>
<ul>
<li>Obtain your Twitter API app account and four pieces of information - your access credentials - CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, and ACCESS_TOKEN_SECRET.</li>
<li>Set four Apache environment variables to store these values, e.g. SetEnv CONSUMER_KEY "***************".</li>
<li>Similarly, set Appache enviornment variables to store your MySQL username and password.</li>
<li>Run controller/test.php with any query, i.e. $query = 'star wars.'</li>
</ul>
You should get something like this in your browser :-)<br>
RT @DepressedDarth: If you're married to someone who doesn't like Star Wars, may divorce be with you<br>
RT @StarWarsDirect: Daisy Ridley training for Star Wars: Episode VIII https://t.co/x024J9hd72<br>
