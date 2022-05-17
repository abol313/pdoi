<!--In the name of kindly generous ALLAH-->
<!--Thanks ALLAH-->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pdo connection test</title>

    <link rel="stylesheet" href="style.css" />
    <script src="script.js" defer></script>
</head>

<body>
    <?php
    $configVarsNames = ['servername', 'username', 'password', 'dbname', 'sql'];
    $configVarsNames = array_filter($configVarsNames, fn ($v) => isset($_GET[$v]));

    $configVars = [];
    if (!empty($configVarsNames)) {
        foreach ($configVarsNames as $name)
            $configVars[$name] = $_GET[$name];
    }
    ?>
    <form>
        <label for="servername">Server name</label>
        <input id="servername" name="servername" placeholder="Server name" <?= isset($configVars['servername']) ? 'value="'.$configVars['servername'].'"' : "" ?> required />

        <label for="username">User name</label>
        <input id="username" name="username" placeholder="User name" <?= isset($configVars['username']) ? 'value="'.$configVars['username'].'"' : "" ?> required />

        <label for="password">Password</label>
        <input id="password" name="password" type="password" placeholder="Password" <?= isset($configVars['password']) ? 'value="'.$configVars['password'].'"' : "" ?> required />

        <label for="dbname">DB name</label>
        <input id="dbname" name="dbname" placeholder="DB name" <?= isset($configVars['dbname']) ? 'value="'.$configVars['dbname'].'"' : "" ?> required />

        <label for="sql">SQL</label>
        <input id="sql" name="sql" placeholder="SQL" <?= isset($configVars['sql']) ? 'value="'.$configVars['sql'].'"' : "" ?> required />

        <button type="submit">Submit</button>
    </form>

    <div class="output">
        <?php
        try {

            $DBServerName = $configVars['servername'];
            $DBUserName = $configVars['username'];
            $DBPassword = $configVars['password'];
            $DBName = $configVars['dbname'];
            $conn = new PDO("mysql:host=$DBServerName;dbname=$DBName;", $DBUserName, $DBPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<h3 class='log'>Connected successfully</h3>";
            echo "<hr/>";

            $sql = $configVars['sql'];

            $dsql = trim($sql);
            $dsql = substr($dsql, 0, 6);
            $dsql = strtolower($dsql);
            $dsql = substr($dsql,0,6);
            $queriable = $dsql=="select";
            if ($queriable) {
                $result = $conn->query($sql);
                $result->setFetchMode(PDO::FETCH_ASSOC);

                echo "<div class='table_container'>";
                echo "<table>";

                //print column names
                echo "<tr>";
                for ($i = 0 ; $i < $result->columnCount() ; $i++) 
                    {echo "<th>".$result->getColumnMeta($i)['name']."</th>";}
                echo "</tr>";


                while($row = $result -> fetch()){
                    echo "<tr>";
                    //var_dump($row);
                    foreach($row as $value)
                        echo "<td>".$value."</td>";
                    echo "</tr>";
                }

                echo "</table>";
                echo "</div>";
            } else {
            }
        } catch (PDOException $e) {
            echo "<h3 class='log error'>Error : " . $e->getMessage() . "</h3>";
        }
        ?>
    </div>
</body>

</html>