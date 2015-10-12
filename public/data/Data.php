<?php

class Data {

    private $db;

    function __construct($db, $user, $pass) {
        $this->db = new PDO('mysql:host=localhost;dbname='.$db.';charset=utf8', $user, $pass);
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    function years() {
        $stmt = $this->db->query('SELECT YEAR(sales.date) AS year FROM sales GROUP BY 1;');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    function products() {
        return $this->db->query('SELECT id, industry_id, name FROM products ORDER BY name;')->fetchAll();
    }

    function industries() {
        return $this->db->query('SELECT id, name FROM industries ORDER BY name;')->fetchAll();
    }

    function salesReps() {
        return $this->db->query('SELECT id, name FROM sales_reps ORDER BY name')->fetchAll();
    }

    function regions() {
        return $this->db->query('SELECT id, name FROM regions ORDER BY name')->fetchAll();
    }

    function quarters () {
        return [1, 2, 3, 4];
    }

    private function createQueryFilters($years, $quarters, $products,
                                        $regions, $industries, $sales) {
        $regions_q = implode(',', array_fill(0, count($regions), '?'));
        $sales_q = implode(',', array_fill(0, count($sales), '?'));
        $years_q = implode(',', array_fill(0, count($years), '?'));
        $quarters_q = implode(',', array_fill(0, count($quarters), '?'));
        $industries_q = implode(',', array_fill(0, count($industries), '?'));
        $products_q = implode(',', array_fill(0, count($products), '?'));

        return 'sales.region IN ('.$regions_q.') AND
                sales.sales_rep IN ('.$sales_q.') AND
                industries.id IN ('.$industries_q.') AND
                products.id IN ('.$products_q.') AND
                YEAR(sales.date) IN ('.$years_q.') AND
                QUARTER(sales.date) IN ('.$quarters_q.')';
    }

    private function bindQueryFiltersParams($stmt, $years, $quarters, $products,
                                            $regions, $industries, $sales) {
        $params = array_merge($regions, $sales,
                              $industries, $products,
                              $years, $quarters);
        foreach ($params as $k => $v)
            $stmt->bindValue($k + 1, $v);
    }

    function revenueByIndustry($years, $quarters, $products, $regions,
                               $industries, $salesReps) {
        
        $stmt = $this->db->prepare('SELECT 
                                     industries.name,
                                     SUM(sales.total)
                                   FROM 
                                     industries, sales, products
                                   WHERE
                                     sales.product = products.id AND
                                     products.industry_id = industries.id AND
                                     '.$this->createQueryFilters($years, $quarters,
                                                                 $products, $regions,
                                                                 $industries, $salesReps).'
                                   GROUP BY industries.id
                                   ORDER BY 1');
        $this->bindQueryFiltersParams($stmt, $years, $quarters, $products, $regions,
                                      $industries, $salesReps);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }

    function revenueByProduct($years, $quarters, $products, $regions,
                              $industries, $salesReps) {
        $stmt = $this->db->prepare('SELECT 
                                     products.name,
                                     SUM(sales.total)
                                   FROM 
                                     industries, sales, products
                                   WHERE
                                     sales.product = products.id AND
                                     products.industry_id = industries.id AND
                                     '.$this->createQueryFilters($years, $quarters,
                                                                 $products, $regions,
                                                                 $industries, $salesReps).'
                                   GROUP BY products.id
                                   ORDER BY 1');
        $this->bindQueryFiltersParams($stmt, $years, $quarters, $products, $regions,
                                      $industries, $salesReps);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }

    function revenueBySalesRep($years, $quarters, $products, $regions,
                               $industries, $salesReps) {
        $stmt = $this->db->prepare('SELECT 
                                     sales_reps.name,
                                     SUM(sales.total)
                                   FROM 
                                     industries, sales, products, sales_reps
                                   WHERE
                                     sales.product = products.id AND
                                     products.industry_id = industries.id AND
                                     sales_reps.id = sales.sales_rep AND
                                     '.$this->createQueryFilters($years, $quarters,
                                                                 $products, $regions,
                                                                 $industries, $salesReps).'
                                   GROUP BY sales_reps.id
                                   ORDER BY 1');
        $this->bindQueryFiltersParams($stmt, $years, $quarters, $products, $regions,
                                      $industries, $salesReps);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }

    function revenueByQuarter($years, $quarters, $products, $regions,
                              $industries, $salesReps) {
        $quarters = array_map('intval', $quarters);
        $query = 'SELECT YEAR(sales.date)';
        foreach ($quarters as $q) {
            $query .= ',SUM(CASE WHEN QUARTER(sales.date)='.$q.' THEN sales.total ELSE 0 END) ';
        }
        $query .= 'FROM industries, sales, products
                   WHERE sales.product = products.id AND
                         products.industry_id = industries.id AND
                         '.$this->createQueryFilters($years, $quarters,
                                                     $products, $regions,
                                                     $industries, $salesReps).'
                   GROUP BY 1
                   ORDER BY 1';
        $stmt = $this->db->prepare($query);
        $this->bindQueryFiltersParams($stmt, $years, $quarters, $products, $regions,
                                      $industries, $salesReps);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }
}