function convertToK(number) {
  if (number < 1000) {
    return number.toString(); // Return the number as is if it's less than 1000
  }
  var units = ["K", "M", "B", "T"]; // Array of unit abbreviations
  var suffix = "";
  var roundedNum = 0;
  for (var i = units.length - 1; i >= 0; i--) {
    var decimal = Math.pow(1000, i + 1);
    if (number <= -decimal || number >= decimal) {
      roundedNum = Math.round((number / decimal) * 10) / 10; // Round to 1 decimal place
      suffix = units[i];
      break;
    }
  }
  return roundedNum.toString() + suffix;
}

function getGlobalInfo() {
    $.getJSON("?r=dashboard&f=get_global_info_new&p0=" + current_store_id + "&p1=" + $("#date_range").val(), function (data) {

        $("#total_stock_cost").html(data.default_currency_symbol+""+data.total_stock_cost);
        $("#total_customers_remain").html(data.default_currency_symbol+""+data.total_customers_remain_p);
        $("#total_supplier_remain").html(data.default_currency_symbol+""+data.total_supplier_remain_usd);
        $("#interna_call_balance").html(data.default_currency_symbol+""+data.interna_call_balance);
        $("#mobile_stock_value_mtc").html(data.default_currency_symbol+""+data.mobile_stock_value_mtc);
        $("#mobile_stock_value_alfa").html(data.default_currency_symbol+""+data.mobile_stock_value_alfa);
        /*$("#total_cash_sales").html(data.total_cash_sales);
        $("#total_debts_sales").html(data.total_debts_sales);
        $("#total_creditcard_sales").html(data.total_creditcard_sales);
        $("#total_cheque_sales").html(data.total_cheque_sales);
        $("#total_expenses").html(data.total_expenses);
        $("#total_profit").html(data.total_profit);
        $("#total_sales").html(data.total_sales);
        $("#profit_margin").html(data.profit_margin + " %");
        $("#total_returns").html(data.total_returns);
        $("#total_items").html(data.total_items);

        $("#total_invoices_taxes").html(data.total_invoices_taxes);
        $("#total_invoices_freight").html(data.total_invoices_freight);

        $("#total_suppliers_payment").html(data.total_suppliers_payment);

        $("#total_customers_payments").html(data.total_customers_payments);

        
        

        

        


        $("#total_supplier_remain_usd").html(data.total_supplier_remain_usd);
        $("#total_supplier_remain_lbp").html(data.total_supplier_remain_lbp);

        

       
        $("#total_wasting").html(data.total_wasting);
        $("#total_cashbox").html(data.cashbox);*/


    }).done(function () {
        
    });
}