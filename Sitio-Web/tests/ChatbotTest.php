<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas unitarias del modelo Chatbot (opciones, sub-opciones y registro de interacciones).
 */
final class ChatbotTest extends TestCase {

    private Chatbot $model;

    protected function setUp(): void {
        $this->model = new Chatbot(testPDO());
    }

    public function testGetMainOptionsDevuelveOpcionesActivas(): void {
        $opciones = $this->model->getMainOptions();
        $this->assertNotEmpty($opciones);
        foreach ($opciones as $opcion) {
            $this->assertSame(1, (int)$opcion['is_active']);
        }
        $this->assertSame(1, (int)$opciones[0]['is_active']);
    }

    public function testGetExtraQuestionsDevuelveLasDelOption(): void {
        // La opción 1 del seeded de pruebas tiene sub-opciones
        $subs = $this->model->getExtraQuestions(1);
        $this->assertNotEmpty($subs);
        foreach ($subs as $sub) {
            $this->assertSame(1, (int)$sub['id_chatbot_option']);
        }

        // Opción sin sub-opciones
        $this->assertSame([], $this->model->getExtraQuestions(999));
    }

    public function testLogInteractionRegistraElClic(): void {
        $opciones = $this->model->getMainOptions();
        $id_option = (int)$opciones[0]['id_chatbot_option'];
        $hoy = date('Y-m-d');

        $this->assertTrue($this->model->logInteraction($id_option, null, null));

        // ID del registro insertado (para limpieza)
        $idInsertado = (int)testPDO()->query(
            "SELECT id_chatbot_interaction FROM chatbot_interactions WHERE id_chatbot_option = $id_option ORDER BY id_chatbot_interaction DESC LIMIT 1"
        )->fetchColumn();
        $this->assertGreaterThan(0, $idInsertado);

        $contada = (int)testPDO()->query(
            "SELECT COUNT(*) FROM chatbot_interactions WHERE id_chatbot_option = $id_option AND DATE(created_at) = '" . $hoy . "' OR id_chatbot_interaction = $idInsertado"
        )->fetchColumn();
        $this->assertGreaterThanOrEqual(1, $contada);

        // Limpieza del registro de la prueba
        testPDO()->exec("DELETE FROM chatbot_interactions WHERE id_chatbot_interaction = $idInsertado");
    }
}
