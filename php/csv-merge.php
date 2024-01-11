<?php
if (isset($_POST['submit'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $csvHeader = null;
        $csvData = [];
        foreach ($_FILES['sourceCsvs']['tmp_name'] as $currentCsv) {
            $file = fopen($currentCsv, 'r');
            $currentHeader = fgetcsv($file);
            if ($csvHeader === null) {
                $csvHeader = $currentHeader;
            } elseif ($csvHeader !== $currentHeader) {
                echo "Error: CSV files have different headers.";
                exit;
            }
            while ($row = fgetcsv($file)) {
                $csvData[] = $row;
            }
            fclose($file);
        }
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="merged_'.$SERVER["RESQUEST_TIME"].'.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, $csvHeader);
        foreach ($csvData as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>CSV Merge</title>
        <style>
            body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        #formContainer {
            margin: 50px auto;
            width: 80%;
            max-width: 400px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);

        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="file"] {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        #fileNames {
            margin-bottom: 10px;
        }

        fieldset {
            border: none;
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
        }

        input[type="submit"], button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            margin: 0px 5px;
        }

        button {
            background-color: #f44336;
            margin: 0px 5px;
        }
        </style>
    </head>
    <body>
        <div id="formContainer">
            <form action="csv-merge.php" method="post" enctype="multipart/form-data">
                <input type="file" name="sourceCsvs[]" multiple="multiple" accept=".csv"/>
                <p id="fileNames"></p>
                <fieldset>
                <input type="submit" name="submit" value="Merge" />
                <button type="button" onclick="e.preventDefault();" id="clearButton">Clear</button>
                </fieldset>
            </form>
        </div>
    </body>
    <script>
        let fileInput = document.getElementsByName('sourceCsvs[]')[0];
        fileInput.onchange = function(){
        let fileNameDisplay = document.getElementById('fileNames');
        fileNameDisplay.innerHTML = '<b>Merging</b><ul>';
        for(let i = 0; i < this.files.length; i++) {
            fileNameDisplay.innerHTML += "<li>" + this.files[i].name + '</li>';
        }
        fileNameDisplay.innerHTML += '</ul>';
        }
        let clearButton = document.getElementById('clearButton');
        clearButton.onclick = function() {
            fileInput.value = '';
            document.getElementById('fileNames').innerHTML = '';
        }
    </script>
</html>
