<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>UPSILON - Barcode Printing</title>
    <script src="libraries/jquery-3.1.1.min.js" type="text/javascript"></script>
    <script src="libraries/JsBarcode-master/dist/JsBarcode.all.js"></script>
    <script>
        Number.prototype.zeroPadding = function(){
                var ret = "" + this.valueOf();
                return ret.length == 1 ? "0" + ret : ret;
        };
        $( document ).ready(function() {
            var _data = []
            $.getJSON("?r=barcode&f=get_info&p0=1", function (data) {
               _data = data;
            }).done(function () {
                //JsBarcode("#barcode", "96385074", {format: "EAN8",height: 20,fontSize: 16,flat: true,textMargin: 0,marginLeft: 0,marginTop: 0,width:2});
                
                //JsBarcode("#barcode", ""+_data[0].barcode, {format: "MSI",height: 20,fontSize: 18,flat: true,textMargin: 0,marginLeft: 0,marginTop: 0,width:1});
                
                JsBarcode("#barcode", "1234", {
                    format: "CODE39",width:1,height: 10,fontSize: 14,marginLeft: 5,marginTop:2,font: "monospace"
                });
                
                setTimeout(function(){window.print();},500);
                
                
            });
            
             
        });
    </script>
    <style>
        body{
            margin: 0px;
            padding-left: 1px;
            padding-right: 1px;
            padding-top: 0px;
            padding-bottom: 0px;
        }
    </style>
</head>
<body>
    <table style="margin-left: 5px; padding: 0px; width: 90%; margin-right: 0px;">
        <tr>
            <td style="border: 1px solid #000; height: 10px; font-size: 10px; font-family: monospace">Store Name</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; height: 16px;"><img id="barcode" /></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; height: 10px; font-size: 10px; font-family: monospace">Description</td>
        </tr>
    </table>
</body>
</html>

