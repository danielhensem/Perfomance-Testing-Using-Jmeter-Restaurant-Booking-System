/*
   Licensed to the Apache Software Foundation (ASF) under one or more
   contributor license agreements.  See the NOTICE file distributed with
   this work for additional information regarding copyright ownership.
   The ASF licenses this file to You under the Apache License, Version 2.0
   (the "License"); you may not use this file except in compliance with
   the License.  You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/
var showControllersOnly = false;
var seriesFilter = "";
var filtersOnlySampleSeries = true;

/*
 * Add header in statistics table to group metrics by category
 * format
 *
 */
function summaryTableHeader(header) {
    var newRow = header.insertRow(-1);
    newRow.className = "tablesorter-no-sort";
    var cell = document.createElement('th');
    cell.setAttribute("data-sorter", false);
    cell.colSpan = 1;
    cell.innerHTML = "Requests";
    newRow.appendChild(cell);

    cell = document.createElement('th');
    cell.setAttribute("data-sorter", false);
    cell.colSpan = 3;
    cell.innerHTML = "Executions";
    newRow.appendChild(cell);

    cell = document.createElement('th');
    cell.setAttribute("data-sorter", false);
    cell.colSpan = 7;
    cell.innerHTML = "Response Times (ms)";
    newRow.appendChild(cell);

    cell = document.createElement('th');
    cell.setAttribute("data-sorter", false);
    cell.colSpan = 1;
    cell.innerHTML = "Throughput";
    newRow.appendChild(cell);

    cell = document.createElement('th');
    cell.setAttribute("data-sorter", false);
    cell.colSpan = 2;
    cell.innerHTML = "Network (KB/sec)";
    newRow.appendChild(cell);
}

/*
 * Populates the table identified by id parameter with the specified data and
 * format
 *
 */
function createTable(table, info, formatter, defaultSorts, seriesIndex, headerCreator) {
    var tableRef = table[0];

    // Create header and populate it with data.titles array
    var header = tableRef.createTHead();

    // Call callback is available
    if(headerCreator) {
        headerCreator(header);
    }

    var newRow = header.insertRow(-1);
    for (var index = 0; index < info.titles.length; index++) {
        var cell = document.createElement('th');
        cell.innerHTML = info.titles[index];
        newRow.appendChild(cell);
    }

    var tBody;

    // Create overall body if defined
    if(info.overall){
        tBody = document.createElement('tbody');
        tBody.className = "tablesorter-no-sort";
        tableRef.appendChild(tBody);
        var newRow = tBody.insertRow(-1);
        var data = info.overall.data;
        for(var index=0;index < data.length; index++){
            var cell = newRow.insertCell(-1);
            cell.innerHTML = formatter ? formatter(index, data[index]): data[index];
        }
    }

    // Create regular body
    tBody = document.createElement('tbody');
    tableRef.appendChild(tBody);

    var regexp;
    if(seriesFilter) {
        regexp = new RegExp(seriesFilter, 'i');
    }
    // Populate body with data.items array
    for(var index=0; index < info.items.length; index++){
        var item = info.items[index];
        if((!regexp || filtersOnlySampleSeries && !info.supportsControllersDiscrimination || regexp.test(item.data[seriesIndex]))
                &&
                (!showControllersOnly || !info.supportsControllersDiscrimination || item.isController)){
            if(item.data.length > 0) {
                var newRow = tBody.insertRow(-1);
                for(var col=0; col < item.data.length; col++){
                    var cell = newRow.insertCell(-1);
                    cell.innerHTML = formatter ? formatter(col, item.data[col]) : item.data[col];
                }
            }
        }
    }

    // Add support of columns sort
    table.tablesorter({sortList : defaultSorts});
}

$(document).ready(function() {

    // Customize table sorter default options
    $.extend( $.tablesorter.defaults, {
        theme: 'blue',
        cssInfoBlock: "tablesorter-no-sort",
        widthFixed: true,
        widgets: ['zebra']
    });

    var data = {"OkPercent": 75.0, "KoPercent": 25.0};
    var dataset = [
        {
            "label" : "FAIL",
            "data" : data.KoPercent,
            "color" : "#FF6347"
        },
        {
            "label" : "PASS",
            "data" : data.OkPercent,
            "color" : "#9ACD32"
        }];
    $.plot($("#flot-requests-summary"), dataset, {
        series : {
            pie : {
                show : true,
                radius : 1,
                label : {
                    show : true,
                    radius : 3 / 4,
                    formatter : function(label, series) {
                        return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">'
                            + label
                            + '<br/>'
                            + Math.round10(series.percent, -2)
                            + '%</div>';
                    },
                    background : {
                        opacity : 0.5,
                        color : '#000'
                    }
                }
            }
        },
        legend : {
            show : true
        }
    });

    // Creates APDEX table
    createTable($("#apdexTable"), {"supportsControllersDiscrimination": true, "overall": {"data": [0.75, 500, 1500, "Total"], "isController": false}, "titles": ["Apdex", "T (Toleration threshold)", "F (Frustration threshold)", "Label"], "items": [{"data": [0.0, 500, 1500, "02 - Submit Customer Info"], "isController": false}, {"data": [1.0, 500, 1500, "03 - Load Menu Page"], "isController": false}, {"data": [1.0, 500, 1500, "01 - Load Home Page-0"], "isController": false}, {"data": [1.0, 500, 1500, "01 - Load Home Page-1"], "isController": false}, {"data": [1.0, 500, 1500, "03 - Load Menu Page-0"], "isController": false}, {"data": [1.0, 500, 1500, "05 - Load Payment Page"], "isController": false}, {"data": [1.0, 500, 1500, "03 - Load Menu Page-1"], "isController": false}, {"data": [0.0, 500, 1500, "04 - Save Cart (JSON)"], "isController": false}, {"data": [1.0, 500, 1500, "01 - Load Home Page"], "isController": false}, {"data": [0.0, 500, 1500, "06 - Process Payment"], "isController": false}, {"data": [1.0, 500, 1500, "05 - Load Payment Page-1"], "isController": false}, {"data": [1.0, 500, 1500, "05 - Load Payment Page-0"], "isController": false}]}, function(index, item){
        switch(index){
            case 0:
                item = item.toFixed(3);
                break;
            case 1:
            case 2:
                item = formatDuration(item);
                break;
        }
        return item;
    }, [[0, 0]], 3);

    // Create statistics table
    createTable($("#statisticsTable"), {"supportsControllersDiscrimination": true, "overall": {"data": ["Total", 120, 30, 25.0, 6.175000000000003, 1, 110, 4.0, 8.900000000000006, 9.949999999999989, 107.89999999999992, 9.762447120078098, 28.88136720021152, 1.8624759752277904], "isController": false}, "titles": ["Label", "#Samples", "FAIL", "Error %", "Average", "Min", "Max", "Median", "90th pct", "95th pct", "99th pct", "Transactions/s", "Received", "Sent"], "items": [{"data": ["02 - Submit Customer Info", 10, 10, 100.0, 3.1, 1, 6, 3.0, 5.800000000000001, 6.0, 6.0, 1.1580775911986103, 0.6050503039953677, 0.2808111971627099], "isController": false}, {"data": ["03 - Load Menu Page", 10, 0, 0.0, 6.5, 5, 8, 6.5, 7.9, 8.0, 8.0, 1.1584800741427246, 6.551521591172382, 0.2749127519694161], "isController": false}, {"data": ["01 - Load Home Page-0", 10, 0, 0.0, 14.0, 2, 100, 4.5, 90.80000000000004, 100.0, 100.0, 1.1436413540713632, 0.32946699165141813, 0.13513730844007318], "isController": false}, {"data": ["01 - Load Home Page-1", 10, 0, 0.0, 4.8, 3, 9, 4.5, 8.8, 9.0, 9.0, 1.1566042100393246, 6.208841154869303, 0.13779854846171638], "isController": false}, {"data": ["03 - Load Menu Page-0", 10, 0, 0.0, 2.4000000000000004, 2, 3, 2.0, 3.0, 3.0, 3.0, 1.1590171534538711, 0.33276469054242, 0.13695417535929533], "isController": false}, {"data": ["05 - Load Payment Page", 10, 0, 0.0, 6.9, 5, 10, 6.5, 9.9, 10.0, 10.0, 1.1575413821044103, 6.546213031022109, 0.34364509781224684], "isController": false}, {"data": ["03 - Load Menu Page-1", 10, 0, 0.0, 3.8, 3, 5, 4.0, 4.9, 5.0, 5.0, 1.1588828369451847, 6.221073197937189, 0.1380700254954224], "isController": false}, {"data": ["04 - Save Cart (JSON)", 10, 10, 100.0, 3.9000000000000004, 1, 9, 4.0, 8.600000000000001, 9.0, 9.0, 1.1582117211026177, 0.6051203816307621, 0.3642032951123465], "isController": false}, {"data": ["01 - Load Home Page", 10, 0, 0.0, 19.1, 6, 110, 9.0, 100.30000000000004, 110.0, 110.0, 1.1429877700308608, 6.465024574237055, 0.27123635558349524], "isController": false}, {"data": ["06 - Process Payment", 10, 10, 100.0, 3.1, 1, 8, 3.0, 7.600000000000001, 8.0, 8.0, 1.1619800139437602, 0.60708916744132, 0.22014074482918894], "isController": false}, {"data": ["05 - Load Payment Page-1", 10, 0, 0.0, 4.199999999999999, 3, 8, 4.0, 7.700000000000001, 8.0, 8.0, 1.157809424568716, 6.215310944193585, 0.13794213847400716], "isController": false}, {"data": ["05 - Load Payment Page-0", 10, 0, 0.0, 2.3000000000000003, 2, 4, 2.0, 3.9000000000000004, 4.0, 4.0, 1.1586142973004288, 0.3326490267639903, 0.20592558799675587], "isController": false}]}, function(index, item){
        switch(index){
            // Errors pct
            case 3:
                item = item.toFixed(2) + '%';
                break;
            // Mean
            case 4:
            // Mean
            case 7:
            // Median
            case 8:
            // Percentile 1
            case 9:
            // Percentile 2
            case 10:
            // Percentile 3
            case 11:
            // Throughput
            case 12:
            // Kbytes/s
            case 13:
            // Sent Kbytes/s
                item = item.toFixed(2);
                break;
        }
        return item;
    }, [[0, 0]], 0, summaryTableHeader);

    // Create error table
    createTable($("#errorsTable"), {"supportsControllersDiscrimination": false, "titles": ["Type of error", "Number of errors", "% in errors", "% in all samples"], "items": [{"data": ["404/Not Found", 30, 100.0, 25.0], "isController": false}]}, function(index, item){
        switch(index){
            case 2:
            case 3:
                item = item.toFixed(2) + '%';
                break;
        }
        return item;
    }, [[1, 1]]);

        // Create top5 errors by sampler
    createTable($("#top5ErrorsBySamplerTable"), {"supportsControllersDiscrimination": false, "overall": {"data": ["Total", 120, 30, "404/Not Found", 30, "", "", "", "", "", "", "", ""], "isController": false}, "titles": ["Sample", "#Samples", "#Errors", "Error", "#Errors", "Error", "#Errors", "Error", "#Errors", "Error", "#Errors", "Error", "#Errors"], "items": [{"data": ["02 - Submit Customer Info", 10, 10, "404/Not Found", 10, "", "", "", "", "", "", "", ""], "isController": false}, {"data": [], "isController": false}, {"data": [], "isController": false}, {"data": [], "isController": false}, {"data": [], "isController": false}, {"data": [], "isController": false}, {"data": [], "isController": false}, {"data": ["04 - Save Cart (JSON)", 10, 10, "404/Not Found", 10, "", "", "", "", "", "", "", ""], "isController": false}, {"data": [], "isController": false}, {"data": ["06 - Process Payment", 10, 10, "404/Not Found", 10, "", "", "", "", "", "", "", ""], "isController": false}, {"data": [], "isController": false}, {"data": [], "isController": false}]}, function(index, item){
        return item;
    }, [[0, 0]], 0);

});
