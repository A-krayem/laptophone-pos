function print_invoice_id_qz(invoice_id){
    qz.websocket.connect().then(function() {
        return qz.printers.find("XP-80");            
     }).then(function(printer) {
        var config = qz.configs.create(printer);
        var data = [{
            type: 'html',
            format: 'file', // or 'plain' if the data is raw HTML
            data: "index.php?r=printing&f=print_invoice&p0="+invoice_id
        }];
        qz.print(config, data).then(function() {
            endConnection();
        }).catch(function(e) { 
            console.error(e); 
        });
        
     }).catch(function(e) { 
         console.error(e); 
     });
}

function endConnection() {
    if (qz.websocket.isActive()) {
        qz.websocket.disconnect().then(function() {
            
        }).catch(function(e) { 
            console.error(e); 
        });
    } else {
        
    }
}