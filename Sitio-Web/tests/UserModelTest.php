<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas unitarias del modelo User sobre la BD de pruebas.
 * Verifican el hash de contraseñas, la búsqueda por correo y las
 * validaciones de identificadores.
 */
final class UserModelTest extends TestCase {

    private User $model;
    private string $email;

    protected function setUp(): void {
        $this->model = new User(testPDO());
        $this->email = 'phpunit-' . uniqid() . '@test.com';
        $this->model->add([
            'username' => 'Usuario PHPUnit',
            'email' => $this->email,
            'password' => 'passwordseguro123',
            'role' => 'client',
            'description' => 'Creado por la prueba automatizada'
        ]);
    }

    protected function tearDown(): void {
        $user = $this->model->findByEmail($this->email);
        if ($user) {
            $this->model->delete($user->id_user);
        }
    }

    public function testAddGuardaLaContrasenaConHash(): void {
        $user = $this->model->findByEmail($this->email);
        $this->assertNotNull($user);
        $this->assertNotSame('passwordseguro123', $user->password_hash);
        $this->assertTrue(password_verify('passwordseguro123', $user->password_hash));
        $this->assertStringStartsWith('$2y$', $user->password_hash); // bcrypt
    }

    public function testFindByEmailDevuelveElUsuarioCorrecto(): void {
        $user = $this->model->findByEmail($this->email);
        $this->assertSame('Usuario PHPUnit', $user->username);
        $this->assertSame('client', $user->role);
    }

    public function testFindByEmailInexistenteDevuelveNulo(): void {
        $this->assertNull($this->model->findByEmail('nadie-' . uniqid() . '@test.com'));
    }

    public function testFindByIdInvalidoDevuelveNulo(): void {
        $this->assertNull($this->model->findById(0));
        $this->assertNull($this->model->findById(-5));
        $this->assertNull($this->model->findById('abc'));
    }

    public function testGetAllDevuelveUsuariosRegistrados(): void {
        $users = $this->model->getAll();
        $this->assertNotEmpty($users);
        $this->assertContainsOnlyInstancesOf(User::class, $users);
    }

    public function testFindByIdDevuelveElUsuarioCreado(): void {
        $user = $this->model->findByEmail($this->email);
        $encontrado = $this->model->findById($user->id_user);
        $this->assertSame($this->email, $encontrado->email);
    }

    public function testUpdateConIdInvalidoDevuelveFalso(): void {
        $this->assertFalse($this->model->update(0, []));
        $this->assertFalse($this->model->delete(-2));
    }

    public function testUpdateSinPasswordConservaElHash(): void {
        $user = $this->model->findByEmail($this->email);
        $hashOriginal = $user->password_hash;

        $this->model->update($user->id_user, [
            'username' => 'Nombre Editado',
            'email' => $this->email,
            'password' => '', // vacío = no cambiar contraseña
            'role' => 'client',
            'description' => 'editado'
        ]);

        $editado = $this->model->findByEmail($this->email);
        $this->assertSame('Nombre Editado', $editado->username);
        $this->assertSame($hashOriginal, $editado->password_hash);
    }

    public function testDeleteEliminaElRegistro(): void {
        $user = $this->model->findByEmail($this->email);
        $this->assertTrue($this->model->delete($user->id_user));
        $this->assertNull($this->model->findByEmail($this->email));
    }
}
