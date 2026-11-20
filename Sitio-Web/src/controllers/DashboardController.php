<?php
require_once __DIR__ . '/../Models/Dashboard.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/auth.php';

/**
 * Controlador para la visualización del Dashboard de Inteligencia de Negocios.
 */
class DashboardController {
    private Dashboard $dashboardModel;

    public function __construct(PDO $pdo) {
        $this->dashboardModel = new Dashboard($pdo);
    }

    /**
     * Renderiza la suite analítica del sistema asegurando privilegios de administrador.
     */
    public function showAnalyticsSuite() {
        requireAdmin();

        // Atrapamos el periodo de la URL (por defecto 'month')
        $period = $_GET['period'] ?? 'month';

        // Le pasamos el periodo a las consultas
        $kpis = $this->dashboardModel->getSummaryKPIs($period);
        $alerts = $this->dashboardModel->getStockAlerts();
        $funnel = $this->dashboardModel->getConversionFunnelData($period);
        $chatbot = $this->dashboardModel->getChatbotDistributionData($period);
        $trends = $this->dashboardModel->getWeeklyTrendsData(); // Se queda fijo en 7 días

        return view('admin/dashboard.index', [
            'kpis' => $kpis,
            'alerts' => $alerts,
            'funnel' => $funnel,
            'chatbot' => $chatbot,
            'trends' => $trends,
            'currentPeriod' => $period // Lo pasamos a la vista para pintar el botón activo
        ]);
    }

    /**
     * Endpoint JSON para la actualización asíncrona del Dashboard (AJAX/Fetch).
     * Mejora de Rendimiento del Plan de Calidad: al cambiar el filtro temporal
     * solo viajan los datos (unos KB), sin recargar la página completa.
     * Protegido por RBAC: responde 401/403 en JSON si no hay sesión de administrador.
     * @return void
     */
    public function apiAnalyticsData() {
        header('Content-Type: application/json; charset=utf-8');

        if (!estaLogueado()) {
            http_response_code(401);
            echo json_encode(['error' => 'No autenticado']);
            return;
        }
        if (!esAdmin()) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso restringido a administradores']);
            return;
        }

        // Lista blanca de periodos válidos (nunca se concatena el valor al SQL)
        $period = $_GET['period'] ?? 'month';
        if (!in_array($period, ['day', 'week', 'month', 'year'], true)) {
            $period = 'month';
        }

        echo json_encode([
            'period' => $period,
            'kpis' => $this->dashboardModel->getSummaryKPIs($period),
            'alerts' => $this->dashboardModel->getStockAlerts(),
            'funnel' => $this->dashboardModel->getConversionFunnelData($period),
            'chatbot' => $this->dashboardModel->getChatbotDistributionData($period),
            'trends' => $this->dashboardModel->getWeeklyTrendsData()
        ]);
    }
}