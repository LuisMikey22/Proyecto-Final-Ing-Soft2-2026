<?php

/**
 * Modelo del Dashboard administrativo: consultas de KPIs, embudo de conversión,
 * distribución del chatbot y tendencias semanales para las gráficas con Chart.js.
 * Optimizado en la Mejora de Rendimiento del Plan de Calidad: se eliminó el
 * problema N+1 de la gráfica de tendencias (14 consultas por carga) y el código
 * muerto que referenciaba columnas inexistentes (added_at / viewed_at).
 */
class Dashboard {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Genera la condición WHERE del filtro temporal.
     * Usa una lista blanca de periodos: el valor del usuario nunca se concatena
     * directamente al SQL (previene inyección SQL en los filtros por fecha).
     * @param string $column Columna temporal (siempre created_at en el esquema actual).
     * @param string $period Periodo solicitado: day, week, month o year.
     * @return string Condición SQL segura.
     */
    private function getDateCondition($column, $period) {
        switch ($period) {
            case 'day':   return "DATE($column) = CURRENT_DATE()";
            case 'week':  return "YEARWEEK($column, 1) = YEARWEEK(CURRENT_DATE(), 1)";
            case 'year':  return "YEAR($column) = YEAR(CURRENT_DATE())";
            case 'month':
            default:      return "MONTH($column) = MONTH(CURRENT_DATE()) AND YEAR($column) = YEAR(CURRENT_DATE())";
        }
    }

    /**
     * KPIs principales: ingresos del periodo, pedidos pendientes y consultas al bot.
     * @param string $period Periodo del filtro (day/week/month/year).
     * @return array Arreglo asociativo con los tres indicadores.
     */
    public function getSummaryKPIs($period = 'month') {
        try {
            $dateCond = $this->getDateCondition('created_at', $period);

            $stmtSales = $this->pdo->query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'paid' AND $dateCond");
            $stmtOrders = $this->pdo->query("SELECT COUNT(*) as total FROM orders WHERE status = 'pending' AND $dateCond");
            $stmtBot = $this->pdo->query("SELECT COUNT(*) as total FROM chatbot_interactions WHERE $dateCond");

            return [
                'monthly_sales' => (float)($stmtSales->fetch(PDO::FETCH_ASSOC)['total'] ?? 0),
                'pending_orders' => (int)($stmtOrders->fetch(PDO::FETCH_ASSOC)['total'] ?? 0),
                'bot_interactions_today' => (int)($stmtBot->fetch(PDO::FETCH_ASSOC)['total'] ?? 0)
            ];
        } catch(PDOException $e) { return ['monthly_sales' => 0, 'pending_orders' => 0, 'bot_interactions_today' => 0]; }
    }

    /**
     * Alertas de stock mínimo (RF08): usa el umbral configurable de cada producto
     * (columna minimum_stock_alert) en lugar de un valor fijo.
     * @return array Productos por debajo de su umbral, ordenados por urgencia.
     */
    public function getStockAlerts() {
        $stmt = $this->pdo->query("SELECT id_product, name, stock FROM products WHERE stock <= minimum_stock_alert ORDER BY stock ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Datos del embudo de conversión: Visitas -> Carrito -> Checkout -> Pagado.
     * @param string $period Periodo del filtro temporal.
     * @return array Volúmenes por etapa.
     */
    public function getConversionFunnelData($period = 'month') {
        try {
            $cond = $this->getDateCondition('created_at', $period);

            $views = $this->pdo->query("SELECT COUNT(*) as total FROM product_views WHERE $cond")->fetch()['total'] ?? 0;
            $carts = $this->pdo->query("SELECT COUNT(*) as total FROM cart_items WHERE $cond")->fetch()['total'] ?? 0;
            $orders = $this->pdo->query("SELECT COUNT(*) as total FROM orders WHERE $cond")->fetch()['total'] ?? 0;
            $paid = $this->pdo->query("SELECT COUNT(*) as total FROM orders WHERE status = 'paid' AND $cond")->fetch()['total'] ?? 0;

            return ['Visitas' => (int)$views, 'Carrito' => (int)$carts, 'Checkout' => (int)$orders, 'Pagado' => (int)$paid];
        } catch(PDOException $e) { return ['Visitas' => 0, 'Carrito' => 0, 'Checkout' => 0, 'Pagado' => 0]; }
    }

    /**
     * Distribución de consultas del chatbot por opción principal (para la gráfica de anillo).
     * @param string $period Periodo del filtro temporal.
     * @return array Temas con su total de clics.
     */
    public function getChatbotDistributionData($period = 'month') {
        $botCond = $this->getDateCondition('i.created_at', $period);
        $sql = "SELECT o.button_text as topic, COUNT(*) as total
                FROM chatbot_interactions i
                JOIN chatbot_options o ON i.id_chatbot_option = o.id_chatbot_option
                WHERE $botCond GROUP BY o.id_chatbot_option";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tendencias de los últimos 7 días: visitas a productos y órdenes pagadas.
     * Optimización: 2 consultas agrupadas por día en lugar de 14 consultas
     * individuales (una por día y por métrica). Corrige además el bug por el que
     * la serie de visitas nunca se ejecutaba y la línea salía vacía.
     * @return array labels, views y sales alineados por día.
     */
    public function getWeeklyTrendsData() {
        $days = []; $views = []; $sales = [];
        $since = date('Y-m-d', strtotime('-6 days'));

        // Una sola consulta agrupada para visitas por día
        $stmtViews = $this->pdo->prepare(
            "SELECT DATE(created_at) as day, COUNT(*) as total
             FROM product_views WHERE DATE(created_at) >= ? GROUP BY DATE(created_at)"
        );
        $stmtViews->execute([$since]);
        $viewsByDay = [];
        foreach ($stmtViews->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $viewsByDay[$row['day']] = (int)$row['total'];
        }

        // Una sola consulta agrupada para ventas pagadas por día
        $stmtSales = $this->pdo->prepare(
            "SELECT DATE(created_at) as day, COUNT(*) as total
             FROM orders WHERE status = 'paid' AND DATE(created_at) >= ? GROUP BY DATE(created_at)"
        );
        $stmtSales->execute([$since]);
        $salesByDay = [];
        foreach ($stmtSales->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $salesByDay[$row['day']] = (int)$row['total'];
        }

        // Armar los 7 días en orden, rellenando con 0 los días sin actividad
        for ($i = 6; $i >= 0; $i--) {
            $dateStr = date('Y-m-d', strtotime("-$i days"));
            $days[] = date('d M', strtotime($dateStr));
            $views[] = $viewsByDay[$dateStr] ?? 0;
            $sales[] = $salesByDay[$dateStr] ?? 0;
        }

        return ['labels' => $days, 'views' => $views, 'sales' => $sales];
    }
}
