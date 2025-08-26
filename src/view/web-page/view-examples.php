<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Example list</title>
    <style>
        table{
            border: 1px solid #eaeaea;
            border-collapse: collapse;
            width: 80%;
            margin: auto
        }
        td,th{
            text-align: left;
            padding: 8px;
            border: 1px solid #eaeaea;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($_POST["result"] as $key => $value){
            ?>
            <tr>
                <td><?=$value["FirstName"]?> <?=$value["LastName"]?></td>
                <td><?=$value["Email"]?></td>
                <td><?=ucfirst(strtolower($value["Status"]))?></td>
            </tr>
            <?php
                }
            ?>
        </tbody>
    </table>
</body>

</html>