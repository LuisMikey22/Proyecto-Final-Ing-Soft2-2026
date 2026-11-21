<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas unitarias del modelo Dashboard: KPIs, embudo, chatbot y la
 * gráfica de tendencias corregida (la serie de visitas ya no sale vacía).
 */
final class DashboardModelTest extends TestCase {

    private Dashboard $model;

    protected function setUp(): void {
        $this->model = new Dashboard(testPDO());
    }

    public function testKpisDelAnioCalculanIngresos(): void {
        $kpis = $this->model->getSummaryKPIs('year');
        $this->assertArrayHasKey('monthly_sales', $kpis);
        $this->assertArrayHasKey('pending_orders', $kpis);
        $this->assertArrayHasKey('bot_interactions_today', $kpis);
        $this->assertGreaterThan(0, $kpis['monthly_sales']); // hay ventas pagadas en los datos precargados
    }

    public function testPeriodoInvalidoNoRompeLaConsulta(): void {
        // El filtro usa lista blanca: un valor raro cae al periodo por defecto (mes)
        $kpis = $this->model->getSummaryKPIs("'; DROP TABLE orders; --");
        $this->assertIsArray($kpis);
        $this->assertArrayHasKey('monthly_sales', $kpis);
    }

    public function testEmbudoTieneLasCuatroEtapas(): void {
        $funnel = $this->model->getConversionFunnelData('year');
        $this->assertSame(['Visitas', 'Carrito', 'Checkout', 'Pagado'], array_keys($funnel));
        $this->assertGreaterThan(0, $funnel['Visitas']);
    }

    public function testDistribucionChatbotDelAnioNoEstaVacia(): void {
        $data = $this->model->getChatbotDistributionData('year');
        $this->assertNotEmpty($data);
        $this->assertArrayHasKey('topic', $data[0]);
        $this->assertArrayHasKey('total', $data[0]);
    }

    public function testTendenciasSemanalesTienenSieteDias(): void {
        $trends = $this->model->getWeeklyTrendsData();
        $this->assertCount(7, $trends['labels']);
        $this->assertCount(7, $trends['views']);
        $this->assertCount(7, $trends['sales']);
    }

    public function testTendenciasReflejanVisitasDeHoy(): void {
        // Regresión del bug: antes la consulta de visitas nunca se ejecutaba
        // y la serie 'views' salía siempre vacía.
        // Insertamos la visita con la fecha de PHP para que siempre caiga en "hoy"
        // del filtro del Dashboard (que usa date() de PHP).
        $pdo = testPDO();
        $hoy = date('Y-m-d H:i:s');
        $pdo->exec("INSERT INTO product_views (id_product, created_at) VALUES (1, '" . $hoy . "')");
        $idInsertado = (int)$pdo->query("SELECT id_view FROM product_views ORDER BY id_view DESC LIMIT 1")->fetchColumn();

        $trends = $this->model->getWeeklyTrendsData();
        $this->assertGreaterThanOrEqual(1, end($trends['views'])); // el último día es hoy

        $pdo->exec("DELETE FROM product_views WHERE id_view = $idInsertado");
    }

    public function testAlertasDeStockUsanElUmbralDelProducto(): void {
        $pdo = testPDO();
        $pdo->exec("INSERT INTO products (id_category, name, description, price, stock, minimum_stock_alert)
                    VALUES (1, 'Producto PHPUnit Stock Bajo', 'prueba', 10.00, 2, 5)");
        $idInsertado = (int)$pdo->lastInsertId();

        $alerts = $this->model->getStockAlerts();
        $nombres = array_column($alerts, 'name');
        $this->assertContains('Producto PHPUnit Stock Bajo', $nombres);

        $pdo->exec("DELETE FROM products WHERE id_product = $idInsertado");
    }
}
