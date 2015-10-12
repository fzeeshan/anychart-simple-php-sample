$(function() {
    var revenueByIndustry = anychart.data.set();
    var revenueBySales = anychart.data.set();
    var revenueByProduct = anychart.data.set();
    var revenueByQuarter = anychart.data.set();

    var createCharts = function() {
        var bar = anychart.bar(revenueByIndustry);
        bar.container("rev-by-industry");
        bar.title("Revenue by industry");
        bar.draw();

        var column = anychart.column(revenueBySales);
        column.container("rev-by-sales");
        column.title("Revenue by sales");
        column.draw();

        var pie = anychart.pie(revenueByProduct);
        pie.container("rev-by-product");
        pie.title("Revenue by product");
        pie.draw();

        var line = anychart.line(revenueByQuarter);
        line.container("rev-by-quarter");
        line.title("Revenue by quarter");
        line.draw();
    };

    var updateDataSets = function(data) {
        console.log(data);
        revenueByIndustry.data(data["by_industry"]);
        revenueBySales.data(data["by_sales"]);
        revenueByProduct.data(data["by_product"]);
        revenueByQuarter.data(data["by_quarter"]);
    }

    $.get("./init.php", function(data) {
        setupUI(data, function(state) {
            $.post("./data.php", JSON.stringify(state), updateDataSets)
        });
        createCharts();
    });
});
