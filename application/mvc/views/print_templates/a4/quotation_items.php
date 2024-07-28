<!DOCTYPE HTML>
<html>

<head>
    <title>Customer Statement</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="resources/favicon.png">
    <script src="libraries/jquery-3.1.1.min.js" type="text/javascript"></script>
    <script src="libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js" type="text/javascript"></script>
    <link href="libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <title>Test</title>
    <style type="text/css">
        @page {
            margin: 0
        }

        body {
            margin: 0;
        }

        .sheet {
            margin: 0;
            overflow: hidden;
            position: relative;
            box-sizing: border-box;
            page-break-after: always;
        }

        body.A4 .sheet {
            width: 210mm;
        }

        /*height: 296mm*/

        /** Padding area **/
        .sheet.padding-10mm {
            padding: 10mm
        }

        .sheet.padding-15mm {
            padding: 15mm
        }

        .sheet.padding-20mm {
            padding: 20mm
        }

        .sheet.padding-25mm {
            padding: 25mm
        }

        /** For screen preview **/
        @media screen {
            body {
                background: #e0e0e0
            }

            .sheet {
                background: white;
                box-shadow: 0 .5mm 2mm rgba(0, 0, 0, .3);
                margin: 5mm auto;
            }
        }

        /** Fix for Chrome issue #273306 **/
        @media print {
            body.A4 {
                width: 210mm
            }
        }

        .tdstyle {
            border: 1px solid #000 !important;
            padding-top: 2px !important;
            padding-bottom: 2px !important;
            padding-left: 2px !important;
            padding-right: 2px !important;
        }

        .line {
            width: 100%;
            height: 1px;
            border-bottom: 1px solid black;
        }

        table {
            page-break-inside: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        thead {
            display: table-header-group
        }

        tfoot {
            display: table-footer-group
        }
    </style>
</head>

<body class="A4">
    <section class="sheet padding-10mm">

        <table style="width: 100%; height: 80px;">
            <tr>
                <td style="font-size: 18px;width: 50%;padding-left: 5px;"><?= $data["settings"]["shop_name"] ? "<b>Company: </b>" . $data["settings"]["shop_name"] : ""; ?><br />
                    <span style="font-size:14px"><?= $data["settings"]["address"] ? "<b>Address:</b> " . $data["settings"]["address"] : ""; ?></span><br />
                    <span style="font-size:14px"><?= $data["settings"]["phone_nb"] ? "<b>Phone:</b> " . $data["settings"]["phone_nb"] : "" ?></span>
                </td>
                <td style="width: 50%;text-align: right;vertical-align:baseline">
                    <span style="font-size: 18px;"><b>Quotation:</b> #<?= $data["quotation"]["id"] ?></span><br />
                    <span style="font-size: 14px;"><b>Date:</b> <?= self::date_format_custom((new DateTime())->format('Y-m-d H:i:s')); ?></span><br />
                    <?php 
                    
                    $tmp=explode(" ", $data["quotation"]["expiery_date"]);
                    if ($tmp[0] != "0000-00-00") { ?><span style="font-size: 14px;"><b>Valid Till:</b> <?= (self::date_format_custom($data["quotation"]["expiery_date"])) ?></span><?php } ?>
                </td>
            </tr>
        </table>

        <div class="line"></div>


        <table style="width: 100%; margin-top: 20px;font-size: 16px;text-align: left;vertical-align:top">
            <?php if ($data["customer"]) { ?>
                <?php $mapper = ["address_note", "address_floor", "address_building", "address_building", "address_area"];
                $result = [];
                foreach ($mapper as $value) {
                    if ($data["customer"][0][$value])
                        $result[] = $data["customer"][0][$value];
                } ?>
                <tr>
                    <td style="width:40%;vertical-align:top"><b>Customer:</b> #<?php echo ($data["customer"][0]["id"]) ?></td>
                    <td style="width:60%;vertical-align:top"> <?php if ($result) {  ?><b>Address:</b> <?= implode(", ", $result) ?> <?php } ?></td>
                </tr>
                <tr>
                    <td style="width:40%;vertical-align:top"><b>Name:</b> <?php echo ucfirst($data["customer"][0]["name"]) . " " . ucfirst($data["customer"][0]["middle_name"]) . " " . ucfirst($data["customer"][0]["last_name"]); ?></td>
                    <td style="width:60%;vertical-align:top"> <?php if ($data["customer"][0]["address_city"]) {  ?><b>City:</b> <?= $data["customer"][0]["address_city"] ?> <?php } ?></td>
                </tr>
                <tr> <?php if ($data["customer"][0]["company"]) {  ?>
                        <td style="width:40%;vertical-align:top">
                            <b>Company:</b> <?php echo ucfirst($data["customer"][0]["company"]) ?>
                        </td><?php } ?>
                    <td style="width:<?= $data["customer"][0]["company"] ? 60 : 40 ?>%;vertical-align:top">
                        <?php if ($data["customer"][0]["phone"]) {  ?><b>Phone:</b> <?= $data["customer"][0]["phone"] ?><?php } ?>
                    </td>

                </tr>

            <?php } ?>
        </table>
        <?php
        $showDiscountColumn = false;
        $showVatColumn = false;
        foreach ($data["items"] as $item) {
            if ($item["discount"] > 0)
                $showDiscountColumn = true;
            if ($item["vat"] > 0)
                $showVatColumn = true;
        }
        ?>
        <table style="width: 100%; margin-top: 10px;">
            <thead>
                <tr>
                    <th colspan="6">&nbsp;</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th colspan="6">&nbsp;</th>
                </tr>
            </tfoot>
            <tbody>
                <tr style="height: 30px;">
                    <td class="tdstyle" style="width: 85px;"><b>CODE</b></td>
                    <td class="tdstyle"><b>Description</b></td>
                    <td class="tdstyle" style="text-align:center!important;width: 45px; text-align: left"><b>Qty</b></td>
                    <td class="tdstyle" style="text-align:center!important;width: 80px; text-align: left"><b>Price</b></td>
                    <td class="tdstyle" style="text-align:center!important;width: 80px; text-align: left"><b>Price/Unit</b></td>
                    <?php if ($showDiscountColumn) { ?>
                        <td class="tdstyle" style="text-align:center!important;width: 80px; text-align: left"><b>Discount</b></td>
                    <?php } ?>
                    <?php if ($showVatColumn) { ?>
                        <td class="tdstyle" style="text-align:center!important;width: 80px; text-align: left"><b>Vat</b></td>
                    <?php } ?>
                    <td class="tdstyle" style="text-align:center!important;width: 80px; text-align: left"><b>Total</b></td>
                </tr>
                <?php
                $sumFinalPrices = 0;
                $sumSellingPrice = 0 ?>
                <?php foreach ($data["items"] as $item) { ?>
                    <tr>
                        <td class="tdstyle" style="width: 120px;" ><?= $item["sku_code"] ?></td>
                        <td class="tdstyle"><?= $item["description"] ?></td>
                        <td class="tdstyle" style="text-align:center!important"><?= number_format($item["qty"]) ?></td>
                        <td class="tdstyle" style="text-align:center!important"><?= self::value_format_custom_no_currency($item["selling_price"], $data["settings"]) ?></td>
                        
                        
                        <td class="tdstyle" style="text-align:center!important"><?php
                        if($item["box_qty"]>0){
                            $unit_price=$item["selling_price"]/$item["box_qty"];
                        }else{
                            $unit_price=$item["selling_price"];
                        }
                        
                        echo self::value_format_custom_no_currency($unit_price, $data["settings"]) ?></td>
                        
                            <?php if ($showDiscountColumn) { ?>
                            <td class="tdstyle" style="text-align:center!important"><?= $item["discount"] > 0 ? self::value_format_custom_no_currency($item["discount"], $data["settings"]) . "%" : ""  ?></td>
                        <?php } ?>
                        <?php if ($showVatColumn) { ?>
                            <td class="tdstyle" style="text-align:center!important"> <?= $item["vat"] ? number_format(($item["vat_value"] - 1) * 100) . "%" : ""  ?></td>
                        <?php } ?>
                        <td class="tdstyle" style="text-align:center!important"><?= self::value_format_custom_no_currency($item["final_price"], $data["settings"]) ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <?php if ($showVatColumn) { ?>
                        <td></td>
                    <?php } ?>
                    <?php if ($showDiscountColumn) { ?>
                        <td></td>
                    <?php } ?>

                    <td style="text-align: right;padding-right:10px"><?= number_format($data["quotation"]["discount"]) > 0 ? "Sub Total: " : "Total: " ?></td>
                    <td style="text-align: left;padding-left:3px"><?= self::value_format_custom_no_currency($data["quotation"]['sub_total'], $data["settings"]) ?></td>
                </tr>
                <?php if (number_format($data["quotation"]["discount"]) > 0) { ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <?php if ($showVatColumn) { ?>
                            <td></td>
                        <?php } ?>
                        <?php if ($showDiscountColumn) { ?>
                            <td></td>
                        <?php } ?>
                        <td style="text-align: right;padding-right:10px">Discount: </td>
                        <td style="text-align: left;padding-left:3px">-<?= self::value_format_custom_no_currency($data["quotation"]['discount'], $data["settings"]) ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                         <td></td>
                        <?php if ($showVatColumn) { ?>
                            <td></td>
                        <?php } ?>
                        <?php if ($showDiscountColumn) { ?>
                            <td></td>
                        <?php } ?>
                        <td style="text-align: right;padding-right:10px">Total: </td>
                        <td style="text-align: left;padding-left:3px"><?= self::value_format_custom_no_currency($data["quotation"]['total'], $data["settings"]) ?></td>
                    </tr>
                <?php }  ?>
            </tbody>
        </table>
        <?php if ($data["quotation"]["note"]) { ?>
            <p><b>Note:</b> <?= $data["quotation"]["note"] ?></p>
        <?php } ?>
    </section>
</body>

</html>