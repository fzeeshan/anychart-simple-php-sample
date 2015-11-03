$(function() {
    var revenueByIndustry = anychart.data.set();
    var revenueBySales = anychart.data.set();
    var revenueByProduct = anychart.data.set();
    var revenueByQuarter;

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

        revenueByQuarter = anychart.line(revenueByQuarter);
        revenueByQuarter.container("rev-by-quarter");
        revenueByQuarter.title("Revenue by quarter");
        revenueByQuarter.draw();
    };

    var updateDataSets = function(data) {
        revenueByIndustry.data(data["by_industry"]);
        revenueBySales.data(data["by_sales"]);
        revenueByProduct.data(data["by_product"]);

        revenueByQuarter.removeAllSeries();
        revenueByQuarter.addSeries.apply(
            revenueByQuarter,
            anychart.data.mapAsTable(data["by_quarter"]));
    }

    $.get("./init.php", function(data) {
        setupUI(data, function(state) {
            $.post("./data.php", JSON.stringify(state), updateDataSets)
        });
        createCharts();
    });
});
