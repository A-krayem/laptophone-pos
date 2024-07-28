<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Printable Table with Repeated Header</title>
<style>
    body {
    font-family: Arial, sans-serif;
}


table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}



@media print { 
    header { 
        position: fixed; 
        top: 0; 
        left: 0; 
        right: 0; 
        height: 50px; 
    } 

    footer { 
        position: fixed; 
        bottom: 0; 
        left: 0; 
        right: 0; 
        height: 50px; 
    } 
    

} 

@media print {
  /* Adjusting the page margins */
  @page {
    margin-top: 100px !important;
  }

  /* Applying styles after 100px from the top */
  main {
    margin-top: 100px; /* Ensure content starts after the margin */
  }
}

</style>
</head>
<body>

    <header>This is the header</header> 
    <main> 
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < 50; $i++) { ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td>John Doe</td>
                        <td>30</td>
                        <td>john@example.com</td>
                    </tr>
                <?php } ?>
                <!-- Add more rows as needed -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">Footer content goes here</td>
                </tr>
            </tfoot>
        </table>

        ds
        ds
        d
        sd
        s
        d
        s
        d
        sd
        s
        d
    </main> 
    <footer>This is the footer</footer> 




</body>
</html>
