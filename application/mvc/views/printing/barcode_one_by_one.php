<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Identities</title>
    <script src="libraries/jquery-3.1.1.min.js" type="text/javascript"></script>
    <script src="libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js" type="text/javascript"></script>
    <link href="libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	
    <style>
        body, h1, h2, h3, h4, h5, h6{

        }
    </style>
    <script type="text/javascript">
        var barcodes = [];
        var index = 0;
        
        var from = 46;
        var to = 50;
        
        $(document).ready(function () {
            //print_b();

             //print_now_one_barcode("101MY1071","72","Antonio Abdallah");
             /*print_now_one_barcode("101MY1155","155","Remond Doueihy Elevator1");
            print_now_one_barcode("101MY1156","156","Remond Doueihy Elevator2");
            print_now_one_barcode("101MY1157","157","Remond Doueihy Elevator3");*/
            
            //print_now_one_barcode("104YM365","","يوسف");
            /*print_now_one_barcode("100MY1310","","");
            print_now_one_barcode("100MY1311","","");
            print_now_one_barcode("100MY1312","","");
            print_now_one_barcode("100MY1313","","");
            print_now_one_barcode("100MY1314","","");
            print_now_one_barcode("100MY1315","","");
            print_now_one_barcode("100MY1316","","");
            print_now_one_barcode("100MY1317","","");
            print_now_one_barcode("100MY1318","","");
            print_now_one_barcode("100MY1319","","");
            print_now_one_barcode("100MY1320","","");
            print_now_one_barcode("100MY1321","","");
            
            print_now_one_barcode("100MY1322","","");
            print_now_one_barcode("100MY1323","","");
            print_now_one_barcode("100MY1324","","");
            print_now_one_barcode("100MY1325","","");
            print_now_one_barcode("100MY1326","","");
            print_now_one_barcode("100MY1327","","");
            print_now_one_barcode("100MY1328","","");
            print_now_one_barcode("100MY1329","","");
            print_now_one_barcode("100MY1330","","");
            print_now_one_barcode("100MY1331","","");*/
        
            //print_now_one_barcode("104YM418","","");
            /*print_now_one_barcode("104YM390","291","Ziad Gitani");
            print_now_one_barcode("104YM419","320","Saad Ghaleb");
            print_now_one_barcode("104YM417","318","Simaan Maarawi");
            
            print_now_one_barcode("104YM420","321","Jeannette Maatouk");
            print_now_one_barcode("104YM391","292","Tannous gitani");
            print_now_one_barcode("104YM392","293","Rene Gitani");
            print_now_one_barcode("104YM393","293","Remond Gitani");
            
            print_now_one_barcode("104YM376","277","Parish Gitani");
            
            print_now_one_barcode("104YM446","347","Elias Bou Tannous");
            print_now_one_barcode("104YM456","357","Radwan Bakkour");
            print_now_one_barcode("104YM445","346","Em Halab");
            print_now_one_barcode("104YM416","317","Georges Salim Franjieh");
            
            print_now_one_barcode("104YM222","123","Alfa");
            print_now_one_barcode("104YM203","104","Salim Saadeh");
            print_now_one_barcode("104YM257","158","Estphan douaihy");*/
        
       
            var startidx = 660;
            for(var i=0;i<10;i++){
                //print_now_one_barcode("104YM"+startidx,"","");
                startidx++;
            }
            
        });
        
        function print_b(){
            barcodes = [];
            index = 0;
            $.getJSON("?r=dashboard&f=get_mytec_counters&p0="+from+"&p1="+to, function (data) {
                barcodes = data;
            }).done(function () {
                setInterval(function(){
                    if(index<barcodes.length){
                         print_now();
                         index++;
                    }
                },3000);
            });
        }
        
        function print_now(){
            $.getJSON("?r=dashboard&f=print_barcodes_mytec&p0="+barcodes[index].reference+"&p1="+barcodes[index].barcode+"&p2="+barcodes[index].name, function (data) {

            }).done(function () {
                
            });
        }
        
        function print_now_one_barcode(bcode,reference,name){
            $.getJSON("?r=dashboard&f=print_barcodes_mytec&p0="+reference+"&p1="+bcode+"&p2="+name, function (data) {

            }).done(function () {
                
            });
        }
        
        function pp(){
            from = to;
            to = from+5;
            print_b();
        }
    
    </script>
</head>
<body>
    <input type="button" onclick="pp()" />
</body>
</html>